<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use App\Usergroup;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
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
        // in cas of blank password, we assign one randomly.
        if (is_null($request->password)) {
            $request->password = bcrypt(Str::random(32));
        }

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'email' => $request->email,
        ]);

        // Test if we need to create a new RSA key
        if ($request->public_key == 'create') {
            list($public_key, $private_key) = $user->createRSAKeyPair();
        } else {
            $public_key = $request->public_key_input;

        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        // Associate User's Groups if has been submitted
        if ($request->groups) {
            $user->usergroups()->attach($request->groups);

        }

        $user->save();

        if ($request->public_key == 'create') {
            $signed_URL = URL::signedRoute('file.download', ['filename' => $private_key]);
            return redirect()->route('users.index')
                ->withSuccess(__('user/messages.create.success_private', ['url' => $signed_URL]));
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
        $user->update([
            'email' => $request->email,
            'enabled' => $request->enabled,
        ]);

        // Associate User's Groups
        if ($request->groups) {
            $user->usergroups()->sync($request->groups);
        } else {
            $user->usergroups()->detach();
        }

        // Test if we need to create a new RSA key
        switch ($request->public_key) {
            case 'create':
                list($user->public_key, $private_key) = $user->createRSAKeyPair();
                break;
            case 'import':
                $user->public_key = $request->public_key_input;
                break;
        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $user->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        $user->save();

        if ($request->public_key == 'create') {
            $signed_URL = URL::signedRoute('file.download', ['filename' => $private_key]);
            return redirect()->route('users.index')
                ->withSuccess(__('user/messages.edit.success_private', ['url' => $signed_URL]));
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
                return Helper::addStatusLabel($user->enabled, $user->username);
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
