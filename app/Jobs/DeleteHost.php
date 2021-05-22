<?php

namespace App\Jobs;

use App\Models\Host;
use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteHost
{
    use Dispatchable;

    private Host $host;

    public function __construct(Host $host)
    {
        $this->host = $host;
    }

    public function handle(): bool
    {
        // delete() can return null while we want to return boolean.
        return $this->host->delete() ?? false;
    }
}
