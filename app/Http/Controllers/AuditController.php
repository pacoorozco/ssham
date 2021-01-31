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

class AuditController extends Controller
{
    /**
     * List all logged activities.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('audit.index');
    }

    /**
     * Return all logged activities as JSON object.
     *
     * @param  \Yajra\DataTables\DataTables  $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable): JsonResponse
    {
        $activities = Activity::all();

        return $datatable->collection($activities)
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
