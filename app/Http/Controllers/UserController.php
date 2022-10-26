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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use App\Actions\UpdateUserAction;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(): View
    {
        return view('user.index');
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(UserCreateRequest $request, CreateUserAction $createUser): RedirectResponse
    {
        $user = $createUser(
            username: $request->username(),
            email: $request->email(),
            password: $request->password(),
            role: $request->role()
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

    public function update(UserUpdateRequest $request, UpdateUserAction $updateUser, User $user): RedirectResponse
    {
        $user = $updateUser(
            user: $user,
            email: $request->email(),
            enabled: $request->enabled(),
            role: $request->role()
        );

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password()),
            ]);
        }

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.edit.success', ['name' => $user->username]));
    }

    public function destroy(User $user): RedirectResponse
    {
        $username = $user->username;

        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('user/messages.delete.success', ['name' => $username]));
    }
}
