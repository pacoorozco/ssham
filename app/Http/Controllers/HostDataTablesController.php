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

use App\Models\Host;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class HostDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Host::class);

        $hosts = Host::select([
            'id',
            'hostname',
            'username',
            'synced',
            'status_code',
            'enabled',
        ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('hostname', 'asc');

        return $dataTable->eloquent($hosts)
            ->editColumn('enabled', function (Host $host) {
                return $host->present()->enabledAsBadge();
            })
            ->editColumn('synced', function (Host $host) {
                return $host->present()->pendingSyncAsBadge();
            })
            ->editColumn('status_code', function (Host $host) {
                return $host->present()->statusCode();
            })
            ->addColumn('actions', function (Host $host) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'hosts')
                    ->with('model', $host)
                    ->render();
            })
            ->rawColumns(['enabled', 'synced', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
