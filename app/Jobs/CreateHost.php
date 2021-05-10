<?php

namespace App\Jobs;

use App\Enums\HostStatus;
use App\Http\Requests\HostCreateRequest;
use App\Models\Host;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class CreateHost
{
    use Dispatchable, SerializesModels;

    private string $hostname;

    private string $username;

    private int $port;

    private array $groups;

    private string $authorized_keys_file;

    private HostStatus $status_code;

    /**
     * CreateHost constructor.
     *
     * @param  string  $hostname
     * @param  string  $username
     * @param  int  $port
     * @param  array  $options  - Optional parameters: 'authorized_keys_file', 'groups'
     */
    public function __construct(string $hostname, string $username, int $port, array $options = [])
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->port = $port;

        $this->authorized_keys_file = (string) ($options['authorized_keys_file'] ?? setting('authorized_keys'));
        $this->groups = $options['groups'] ?? [];
        $this->status_code = new HostStatus(HostStatus::INITIAL_STATUS);
    }

    public static function fromRequest(HostCreateRequest $request): self
    {
        return new static(
            $request->hostname(),
            $request->username(),
            $request->port(),
            [
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
            'status_code' => $this->status_code,
        ]);
        $host->groups()->sync($this->groups);

        return $host;
    }
}
