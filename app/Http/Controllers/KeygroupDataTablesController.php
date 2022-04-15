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

use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class KeygroupDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Keygroup::class);

        $groups = Keygroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('keys as keys') // count number of keys in keygroups without loading the models
            ->orderBy('name', 'asc');

        return $dataTable->eloquent($groups)
            ->addColumn('rules', function (Keygroup $group) {
                return $group->present()->rulesCount();
            })
            ->addColumn('actions', function (Keygroup $keygroup) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'keygroups')
                    ->with('model', $keygroup)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
