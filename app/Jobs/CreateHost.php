<?php

namespace App\Jobs;

use App\Http\Requests\HostCreateRequest;
use App\Models\Activity;
use App\Models\Host;

final class CreateHost
{
    private string $hostname;

    private string $username;

    private int $port;

    private array $groups;

    private string $authorized_keys_file;

    /**
     * CreateHost constructor.
     *
     * @param  string  $hostname
     * @param  string  $username
     * @param  array  $options  - Optional parameters: 'authorized_keys_file', 'groups', 'port'
     */
    public function __construct(string $hostname, string $username, array $options = [])
    {
        $this->hostname = $hostname;
        $this->username = $username;

        $this->port = (int) $options['port'];
        $this->authorized_keys_file = $options['authorized_keys_file'];
        $this->groups = $options['groups'] ?? [];
    }

    public static function fromRequest(HostCreateRequest $request): self
    {
        return new CreateHost(
            $request->hostname(),
            $request->username(),
            [
                'port' => $request->port(),
                'authorized_keys_file' => $request->authorized_keys_file(),
                'groups' => $request->groups(),
            ]
        );
    }

    public function handle(): Host
    {
        $host = Host::create([
            'hostname' => $this->hostname,
            'username' => $this->username,
            'port' => $this->port,
            'authorized_keys_file' => $this->authorized_keys_file,
        ]);
        $host->groups()->sync($this->groups);

        activity()
            ->performedOn($host)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create host '%s'.", $host->full_hostname));

        return $host;
    }
}
