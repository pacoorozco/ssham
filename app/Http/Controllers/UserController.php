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
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
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
        return view('user.create');
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
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'email' => $request->email,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('user/messages.create.error'));
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.create.success', ['name' => $user->username]));
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
        return view('user.edit', compact('user'));
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
        try {
            $user->update([
                'email' => $request->email,
                'enabled' => $request->enabled,
            ]);

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
                $user->save();
            }
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('user/messages.edit.error'));
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.edit.success', ['name' => $user->username]));
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
        $username = $user->username;

        // you can not delete yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(__('user/messages.delete.impossible'));
        }

        try {
            $user->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withSuccess(__('user/messages.delete.error'));
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.delete.success', ['name' => $username]));
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
            'enabled'
        ])
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
