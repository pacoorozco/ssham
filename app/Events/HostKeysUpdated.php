<?php

namespace App\Events;

use App\Models\Host;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HostKeysUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * @var \App\Models\Host
     */
    private Host $host;

    public function getHost(): Host
    {
        return $this->host;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Host $host)
    {
        $this->host = $host;
    }
}
