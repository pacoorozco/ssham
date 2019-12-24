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
        $groups = Usergroup::select('name', 'id')->orderBy('name')->get();

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
        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'email' => $request->email,
        ]);

        // Test if we need to create a new RSA key
        if ($request->create_rsa_key == '1') {
            list($request->public_key, $private_key) = $user->createRSAKeyPair();
        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $request->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        // Associate User's Groups if has been submitted
        if ($request->groups) {
            $user->usergroups()->attach($request->groups);

        }

        $user->save();

        if ($request->create_rsa_key == '1') {
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
        $groups = Usergroup::lists('name', 'id')->all();

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
        $private_key = null;
        $user->update($request->all());

        // Test if we need to create a new RSA key
        if ($request->create_rsa_key == '1') {
            list($request->public_key, $private_key) = $user->createRSAKeyPair();
        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $request->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        $user->save();

        // Associate User's Groups
        if ($request->groups) {
            $user->usergroups()->sync($request->groups);
        } else {
            $user->usergroups()->detach();
        }

        $user->save();

        if ($request->create_rsa_key == '1' && !is_null($private_key)) {
            //flash()->overlay(__('user/messages.edit.success_private', array('url' => link_to(route('file.download', ['filename' => $private_key]), 'this link'))));
        } else {
            //flash()->overlay(__('user/messages.edit.success'));
        }

        return redirect()->route('users.index');
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

        //flash()->success(__('user/messages.delete.success'));

        return redirect()->route('users.index');
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
