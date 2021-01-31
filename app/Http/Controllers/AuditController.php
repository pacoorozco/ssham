<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Activity;
use App\Models\Host;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditController extends Controller
{
    public function index()
    {
        return view('audit.index');
    }

    /**
     * Return all logged activities in order to be used as DataTables.
     *
     * @param  Datatables  $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
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
