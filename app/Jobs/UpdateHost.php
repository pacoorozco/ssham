<?php

namespace App\Jobs;

use App\Http\Requests\HostUpdateRequest;
use App\Models\Activity;
use App\Models\Host;

final class UpdateHost
{
    private Host $host;

    private int $port;

    private array $groups;

    private string $authorized_keys_file;

    private bool $enabled;

    /**
     * CreateHost constructor.
     *
     * @param  \App\Models\Host  $host
     * @param  array  $options  - Optional parameters: 'authorized_keys_file', 'enabled', 'groups', 'port'
     */
    public function __construct(Host $host, array $options = [])
    {
        $this->host = $host;
        $this->enabled = (bool) $options['enabled'];
        $this->port = (int) $options['port'];
        $this->authorized_keys_file = (string) $options['authorized_keys_file'];
        $this->groups = $options['groups'] ?? [];
    }

    public static function fromRequest(Host $host, HostUpdateRequest $request): self
    {
        return new UpdateHost(
            $host,
            [
                'enabled' => $request->enabled(),
                'port' => $request->port(),
                'authorized_keys_file' => $request->authorized_keys_file(),
                'groups' => $request->groups(),
            ]
        );
    }

    public function handle(): Host
    {
        $this->host->update([
            'port' => $this->port,
            'authorized_keys_file' => $this->authorized_keys_file,
            'enabled' => $this->enabled,
        ]);
        $this->host->groups()->sync($this->groups, true);

        return $this->host;
    }
}
