<?php

namespace App\Events;

use App\Models\Host;
use Illuminate\Foundation\Events\Dispatchable;

class HostKeysUpdated
{
    use Dispatchable;

    private Host $host;

    public function getHost(): Host
    {
        return $this->host;
    }

    public function __construct(Host $host)
    {
        $this->host = $host;
    }
}
