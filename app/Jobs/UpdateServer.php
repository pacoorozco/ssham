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
use phpseclib3\Crypt\PublicKeyLoader;
use Throwable;

class UpdateServer implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Delete the job if its models no longer exist.
     *
     * @see https://laravel.com/docs/9.x/queues#ignoring-missing-models
     */
    public bool $deleteWhenMissingModels = true;

    protected SFTPPusher $pusher;

    public function __construct(
        protected Host $host
    ) {}

    /**
     * Execute the job.
     *
     *
     * @throws \App\Exceptions\PusherException
     */
    public function handle(): void
    {
        $this->pusher = new SFTPPusher(
            hostname: $this->host->hostname,
            port: $this->host->portOrDefaultSetting(),
            timeout: setting()->get('ssh_timeout', 5),
        );

        $this->connectRemoteServer();

        $this->sendRemoteUpdaterCLI();

        $this->updateRemoteSSHKeys();

        $this->execRemoteUpdater();

        Log::info('Remote server update succeeded.', [
            'hostname' => $this->host->full_hostname,
        ]);

        $this->host->setStatus(HostStatus::SUCCESS_STATUS);

        $this->pusher->disconnect();
    }

    /**
     * @throws \App\Exceptions\PusherException|\phpseclib3\Exception\NoKeyLoadedException
     */
    protected function connectRemoteServer(): void
    {
        $this->pusher->login(
            username: $this->host->username,
            key: PublicKeyLoader::load(setting()->get('private_key'))
        );
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function sendRemoteUpdaterCLI(): void
    {
        $remoteUpdater = Storage::disk('private')->get('ssham-remote-updater.sh');

        if (! is_null($remoteUpdater)) {
            $this->pusher->pushDataTo(
                data: $remoteUpdater,
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
        $sshKeys = $this->host->getSSHKeysForHost();

        // Add the bastion host's SSH key
        $sshKeys->push(setting()->get('public_key'));

        $authorizedKeysFileContent = $sshKeys->join(PHP_EOL).PHP_EOL;

        $this->pusher->pushDataTo(
            data: $authorizedKeysFileContent,
            remotePath: setting()->get('ssham_file'),
            permission: 0600
        );
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function execRemoteUpdater(): void
    {
        $command = setting()->get('cmd_remote_updater').' update '
            .((setting()->get('mixed_mode') == '1') ? 'true ' : 'false ')
            .setting()->get('authorized_keys').' '
            .setting()->get('non_ssham_file').' '
            .setting()->get('ssham_file');

        $this->pusher->exec($command);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Remote server update failed.', [
            'hostname' => $this->host->full_hostname,
            'error' => $exception->getMessage(),
        ]);

        $this->host->setStatus(HostStatus::GENERIC_FAIL_STATUS);
    }
}
