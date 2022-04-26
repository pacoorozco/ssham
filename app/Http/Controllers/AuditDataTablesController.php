<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class AuditDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $activities = Activity::all();

        return $dataTable->collection($activities)
            ->addColumn('timestamp', function (Activity $activity) {
                return $activity->present()->created_at;
            })
            ->addColumn('time', function (Activity $activity) {
                return $activity->present()->activityAge;
            })
            ->addColumn('status', function (Activity $activity) {
                return $activity->present()->statusBadge;
            })
            ->addColumn('causer', function (Activity $activity) {
                return $activity->present()->causerUsername;
            })
            ->rawColumns(['status'])
            ->only(['description', 'status', 'time', 'timestamp', 'causer'])
            ->toJson();
    }
}
