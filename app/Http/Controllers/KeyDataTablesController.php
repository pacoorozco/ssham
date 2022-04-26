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

use App\Http\Requests\KeyCreateRequest;
use App\Http\Requests\KeyUpdateRequest;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PacoOrozco\OpenSSH\KeyPair;
use PacoOrozco\OpenSSH\PublicKey;
use Yajra\DataTables\DataTables;

class KeyDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Key::class);

        $keys = Key::select([
            'id',
            'username',
            'fingerprint',
            'enabled',
        ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('username', 'asc');

        return $dataTable->eloquent($keys)
            ->editColumn('username', function (Key $key) {
                return $key->present()->usernameWithDisabledBadge();
            })
            ->editColumn('enabled', function (Key $key) {
                return $key->present()->enabledAsBadge();
            })
            ->addColumn('actions', function (Key $key) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'keys')
                    ->with('model', $key)
                    ->render();
            })
            ->rawColumns(['username', 'enabled', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
