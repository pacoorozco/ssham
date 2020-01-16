<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Console\Commands;

use App\Host;
use ErrorException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SFTP;

class SendKeysToHosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssham:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SSH keys to managed hosts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hosts = Host::all();
        Log::info('Hosts to be updated: ' . $hosts->count());
        $this->info('Hosts to be updated: ' . $hosts->count());

        // Get SSHAM private key in order to connect to Hosts.
        $key = new RSA();
        $key->loadKey(setting('private_key'));

        foreach ($hosts as $host) {

            Log::debug('Connecting to ' . $host->getFullHostname());
            $sftp = new SFTP($host->hostname, setting('ssh_port'), setting('ssh_timeout'));

            try {
                if (!$sftp->login($host->username, $key)) {

                    // TODO - Set last_error field on Host
                    // TODO - Set last_update on error status on Host

                    $this->error('ERRROR Can\'t auth on ' . $host->getFullHostname());
                    continue;
                }
            } catch (ErrorException $e) {

                // TODO - Set last_error field on Host
                // TODO - Set last_update on error status on Host

                Log::warning('Error connecting to ' . $host->getFullHostname());
                $this->error('Can not connect to ' . $host->getFullHostname() . ': ' . $e->getMessage());
                continue;
            }

            // Send remote_updater script to remote Host.
            try {
                $fileContents = Storage::disk('private')->get('ssham-remote-updater.sh');
                $sftp->put(setting('cmd_remote_updater'), $fileContents);
                $sftp->chmod(0700, setting('cmd_remote_updater'));
            } catch (FileNotFoundException $e) {
                Log::error('SSHAM Remote Updater can not be accessible: ' . $e->getMessage());
                $this->error('SSHAM Remote Updater can not be accessible: ' . $e->getMessage());
            }

            // Send SSHAM authorized file to remote Host.
            $sshKeys = $host->getSSHKeysForHost();
            $sftp->put(setting('ssham_file'), join(PHP_EOL, $sshKeys));
            $sftp->chmod(0600, setting('ssham_file'));

            // Execute remote_updater script on remote Host.
            $command = setting('cmd_remote_updater') . ' update '
                . ((setting('mixed_mode') == '1') ? 'true ' : 'false ')
                . setting('authorized_keys') . ' '
                . setting('non_ssham_file') . ' '
                . setting('ssham_file');

            Log::info('SSH authorized keys file updated successfully on ' . $host->getFullHostname());
            $sftp->enableQuietMode();
            echo $sftp->exec($command);
            $sftp->disconnect();

            // Mark host in sync.
            $host->setSynced(true);
        }
    }
}
