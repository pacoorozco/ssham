<?php

namespace App\Jobs;

use App\Enums\HostStatus;
use App\Events\HostKeysUpdated;
use App\Models\Host;
use App\Services\SFTP\SFTPPusher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\RSA;

class UpdateServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Host       $host;
    protected SFTPPusher $pusher;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Host $host)
    {
        $this->host = $host;

        $this->pusher = new SFTPPusher(
            $this->host->hostname,
            $this->host->port,
            setting()->get('ssh_timeout'),
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->connectRemoteServer();

            $this->sendRemoteUpdaterCLI();

            $this->updateRemoteSSHKeys();

            $this->execRemoteUpdater();
        } catch (\Throwable $exception) {
            $this->host->setStatus(HostStatus::GENERIC_FAIL_STATUS);
            $this->fail($exception);
        }

        $this->host->setStatus(HostStatus::SUCCESS_STATUS);

        $this->pusher->disconnect();
        HostKeysUpdated::dispatch();
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function connectRemoteServer()
    {
        $key = new RSA();
        $key->loadKey(setting()->get('private_key'));
        $this->pusher->login($this->host->username, $key);
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function execRemoteUpdater()
    {
        $command = setting()->get('cmd_remote_updater') . ' update '
            . ((setting()->get('mixed_mode') == '1') ? 'true ' : 'false ')
            . setting()->get('authorized_keys') . ' '
            . setting()->get('non_ssham_file') . ' '
            . setting()->get('ssham_file');

        $this->pusher->exec($command);
    }

    /**
     * @throws \App\Exceptions\PusherException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function sendRemoteUpdaterCLI()
    {
        $remoteUpdater = Storage::disk('private')->get('ssham-remote-updater.sh');
        $this->pusher->pushFileTo($remoteUpdater, setting()->get('cmd_remote_updater'), 0700);
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    protected function updateRemoteSSHKeys()
    {
        $sshKeys = $this->host->getSSHKeysForHost(setting('public_key'));
        $this->pusher->pushDataTo(join(PHP_EOL, $sshKeys), setting()->get('ssham_file'), 0600);
    }
}
