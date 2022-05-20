<?php

namespace App\Jobs;

use App\Enums\HostStatus;
use App\Models\Host;
use App\Services\SFTP\SFTPPusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PacoOrozco\OpenSSH\PrivateKey;

class UpdateServer implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Host $host;
    protected SFTPPusher $pusher;

    public function __construct(Host $host)
    {
        $this->host = $host;
    }

    public function handle(): void
    {
        try {
            $this->pusher = new SFTPPusher(
                hostname: $this->host->hostname,
                port: $this->host->portOrDefaultSetting(),
                timeout: setting()->get('ssh_timeout'),
            );

            $this->connectRemoteServer();

            $this->sendRemoteUpdaterCLI();

            $this->updateRemoteSSHKeys();

            $this->execRemoteUpdater();
        } catch (\Throwable $exception) {
            Log::error("Updating server '" . $this->host->full_hostname . "' got this error: " . $exception->getMessage());

            $this->host->setStatus(HostStatus::GENERIC_FAIL_STATUS());
            $this->fail($exception);
            return;
        }

        $this->host->setStatus(HostStatus::SUCCESS_STATUS());

        $this->pusher->disconnect();
    }

    /**
     * @throws \App\Exceptions\PusherException|\PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey
     */
    protected function connectRemoteServer(): void
    {
        $this->pusher->login(
            username: $this->host->username,
            privateKey: PrivateKey::fromString(setting()->get('private_key'))
        );
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function sendRemoteUpdaterCLI(): void
    {
        $remoteUpdater = Storage::disk('private')->get('ssham-remote-updater.sh');
        if (!is_null($remoteUpdater)) {
            $this->pusher->pushFileTo(
                localPath: $remoteUpdater,
                remotePath: setting()->get('cmd_remote_updater'),
                permission: 0700
            );
        }
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function updateRemoteSSHKeys(): void
    {
        $sshKeys = $this->host->getSSHKeysForHost(setting()->get('public_key'));
        $this->pusher->pushDataTo(
            data: join(PHP_EOL, $sshKeys),
            remotePath: setting()->get('ssham_file'),
            permission: 0600
        );
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function execRemoteUpdater(): void
    {
        $command = setting()->get('cmd_remote_updater') . ' update '
            . ((setting()->get('mixed_mode') == '1') ? 'true ' : 'false ')
            . setting()->get('authorized_keys') . ' '
            . setting()->get('non_ssham_file') . ' '
            . setting()->get('ssham_file');

        $this->pusher->exec($command);
    }
}
