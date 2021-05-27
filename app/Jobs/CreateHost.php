<?php

namespace App\Jobs;

use App\Models\Host;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateHost
{
    use Dispatchable;

    private string $hostname;

    private string $username;

    private bool $enabled;

    private int $port;

    private array $groups;

    private string $authorized_keys_file;

    /**
     * CreateHost constructor.
     *
     * @param  string  $hostname
     * @param  string  $username
     * @param  array  $options  - Optional parameters: 'authorized_keys_file', 'enabled', 'groups', 'port'
     */
    public function __construct(string $hostname, string $username, array $options = [])
    {
        $this->hostname = $hostname;
        $this->username = $username;

        $this->enabled = (bool) $options['enabled'];
        $this->port = (int) $options['port'];
        $this->authorized_keys_file = $options['authorized_keys_file'];
        $this->groups = $options['groups'] ?? [];
    }

    public function handle(): Host
    {
        $host = Host::create([
            'hostname' => $this->hostname,
            'username' => $this->username,
            'enabled' => $this->enabled,
            'port' => $this->port,
            'authorized_keys_file' => $this->authorized_keys_file,
        ]);
        $host->groups()->sync($this->groups);

        return $host;
    }
}
