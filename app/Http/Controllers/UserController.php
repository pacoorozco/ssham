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
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use yajra\Datatables\Datatables;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.index');
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(UserCreateRequest $request): RedirectResponse
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

    public function show(User $user): View
    {
        return view('user.show')
            ->with('user', $user);
    }

    public function edit(User $user): View
    {
        return view('user.edit')
            ->with('user', $user);
    }

    public function update(User $user, UserUpdateRequest $request): RedirectResponse
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

    public function delete(User $user): View
    {
        return view('user.delete')
            ->with('user', $user);
    }

    public function destroy(User $user): RedirectResponse
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

    public function data(Datatables $datatable): JsonResponse
    {
        $users = User::select([
            'id',
            'username',
            'email',
            'enabled',
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
