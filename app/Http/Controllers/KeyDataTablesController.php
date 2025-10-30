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

use App\Models\Key;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class KeyDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Key::class);

        $keys = Key::query()
            ->select([
                'id',
                'name',
                'fingerprint',
                'enabled',
            ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('name');

        return $dataTable->eloquent($keys)
            ->editColumn('name', function (Key $key) {
                return $key->present()->nameWithDisabledBadge();
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
            ->rawColumns(['name', 'enabled', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
