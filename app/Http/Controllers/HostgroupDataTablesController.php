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

use App\Models\Hostgroup;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class HostgroupDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Hostgroup::class);

        $groups = Hostgroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('hosts as hosts') // count number of hosts in hostgroups without loading the models
            ->orderBy('name', 'asc');

        return $dataTable->eloquent($groups)
            ->addColumn('rules', function (Hostgroup $group) {
                return $group->present()->rulesCount();
            })
            ->addColumn('actions', function (Hostgroup $group) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'hostgroups')
                    ->with('model', $group)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
