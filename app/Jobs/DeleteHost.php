<?php

namespace App\Jobs;

use App\Models\Activity;
use App\Models\Host;

final class DeleteHost
{
    private Host $host;

    public function __construct(Host $host)
    {
        $this->host = $host;
    }

    public function handle(): bool
    {
        activity()
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Delete host '%s'.", $this->host->full_hostname));

        return $this->host->delete();
    }
}
