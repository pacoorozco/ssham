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
        return $this->host->delete();
    }
}
