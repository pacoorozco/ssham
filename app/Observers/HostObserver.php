<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Host;

class HostObserver
{
    public function created(Host $host): void
    {
        activity()
            ->performedOn($host)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create host '%s'.", $host->full_hostname));
    }

    public function updated(Host $host): void
    {
        activity()
            ->performedOn($host)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Update host '%s'.", $host->full_hostname));
    }

    public function deleted(Host $host): void
    {
        activity()
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Delete host '%s'.", $host->full_hostname));
    }

    public function restored(Host $host): void
    {
        //
    }

    public function forceDeleted(Host $host): void
    {
        //
    }
}
