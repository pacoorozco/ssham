<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2020 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Libs\RsaSshKey\RsaSshKey;
use App\User;
use App\Usergroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use yajra\Datatables\Datatables;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing user groups
        $groups = Usergroup::orderBy('name')->pluck('name', 'id');

        return view('user.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserCreateRequest $request)
    {
        // Use transaction to ensure that the user is created with all the required data.
        DB::beginTransaction();

        // Test if we need to create a new RSA key
        $private_key_filename = null;
        if ($request->input('public_key') == 'create') {
            $keys = RsaSshKey::create();
            $public_key = $keys['publickey'];
            $private_key_filename = RsaSshKey::createDownloadableFile($keys['privatekey'], $request->input('username') . '.rsa');
        } else {
            $public_key = $request->input('public_key_input');
        }

        // in case of blank password, we assign one randomly.
        $user = User::create([
            'username' => $request->input('username'),
            'password' => ($request->filled('password')
                ? $request->input('password')
                : User::createRandomPassword()
            ),
            'email' => $request->input('email'),
        ]);

        // Associate User's Groups if has been submitted
        $user->usergroups()->attach($request->input('groups'));

        // Attach the RSA SSH public key to the created use (includes a save() method).
        if (!$user->attachPublicKey($public_key)) {
            DB::rollBack(); // RollBack in case of error.
            return redirect()->route('users.create')
                ->withInput()
                ->withErrors(__('user/messages.create.error'));
        }

        // Everything went fine, we can commit the transaction.
        DB::commit();

        if (!is_null($private_key_filename)) {
            return redirect()->route('users.index')
                ->withSuccess(__('user/messages.create.success_private', [
                    'url' => URL::signedRoute('file.download', ['filename' => $private_key_filename])
                ]));
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // Get all existing user groups
        $groups = Usergroup::orderBy('name')->pluck('name', 'id');

        return view('user.edit', compact('user', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User              $user
     * @param UserUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, UserUpdateRequest $request)
    {
        // Use transaction to ensure that the user is updated with all the required data.
        DB::beginTransaction();

        $user->update([
            'email' => $request->input('email'),
            'enabled' => $request->input('enabled'),
        ]);

        // Associate User's Groups
        if ($request->groups) {
            $user->usergroups()->sync($request->groups);
        } else {
            $user->usergroups()->detach();
        }

        // Test if we need to create a new RSA key
        $private_key_filename = null;
        switch ($request->input('public_key')) {
            case 'create':
                $keys = RsaSshKey::create();
                $public_key = $keys['publickey'];
                $private_key_filename = RsaSshKey::createDownloadableFile($keys['privatekey'], $user->username . '.rsa');
                break;
            case 'import':
                $public_key = $request->input('public_key_input');
                break;
            case 'maintain':
            default:
                $public_key = null;
                break;
        }

        // Attach the RSA SSH public key to the created use (includes a save() method).
        if (!$user->attachPublicKey($public_key)) {
            DB::rollBack(); // RollBack in case of error.
            redirect()->route('users.edit')
                ->withInput()
                ->withErrors(__('user/messages.edit.error'));
        }

        // Everything went fine, we can commit the transaction.
        DB::commit();

        if (!is_null($private_key_filename)) {
            return redirect()->route('users.index')
                ->withSuccess(__('user/messages.edit.success_private', [
                    'url' => URL::signedRoute('file.download', ['filename' => $private_key_filename])
                ]));
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.edit.success'));
    }

    /**
     * Remove user.
     *
     * @param User $user
     *
     * @return \Illuminate\View\View
     */
    public function delete(User $user)
    {
        return view('user.delete', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.delete.success'));
    }

    /**
     * Return all Users in order to be used with DataTables
     *
     * @param Datatables $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $users = User::select([
            'id',
            'username',
            'email',
            'fingerprint',
            'enabled'
        ])
            ->withCount('usergroups as groups') // count number of usergroups without loading the models
            ->orderBy('username', 'asc');

        return $datatable->eloquent($users)
            ->editColumn('username', function (User $user) {
                return Helper::addDisabledStatusLabel($user->enabled, $user->username);
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.actions_dd')
                    ->with('model', 'users')
                    ->with('id', $user->id)
                    ->render();
            })
            ->rawColumns(['username', 'actions'])
            ->removeColumn('id')
            ->removeColumn('enabled')
            ->toJson();
    }
}
