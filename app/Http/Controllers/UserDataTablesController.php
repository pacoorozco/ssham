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

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class UserDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = User::select([
            'id',
            'username',
            'email',
            'enabled',
            'auth_type',
        ])
            ->orderBy('username', 'asc');

        return $dataTable->eloquent($users)
            ->editColumn('username', function (User $user) {
                return $user->present()->usernameWithDisabledBadge();
            })
            ->editColumn('enabled', function (User $user) {
                return $user->present()->enabledAsBadge();
            })
            ->addColumn('authentication', function (User $user) {
                return $user->present()->authenticationAsBadge();
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'users')
                    ->with('model', $user)
                    ->render();
            })
            ->rawColumns(['username', 'actions'])
            ->removeColumn(['id', 'tokens'])
            ->toJson();
    }
}
