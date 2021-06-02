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
use App\Jobs\ChangeUserPassword;
use App\Jobs\CreateUser;
use App\Jobs\DeleteUser;
use App\Jobs\UpdateUser;
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
        $user = CreateUser::dispatchSync(
            $request->username(),
            $request->email(),
            $request->password()
        );

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
        UpdateUser::dispatchSync(
            $user,
            $request->email(),
            $request->enabled()
        );

        if ($request->filled('password')) {
            ChangeUserPassword::dispatchSync(
                $user,
                $request->password()
            );
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.edit.success', ['name' => $user->username]));
    }

    public function destroy(User $user): RedirectResponse
    {
        // you can not delete yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(__('user/messages.delete.impossible'));
        }

        DeleteUser::dispatchSync($user);

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.delete.success', ['name' => $user->username]));
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
                return $user->present()->usernameWithDisabledBadge();
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.buttons-to-show-and-edit-actions')
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
