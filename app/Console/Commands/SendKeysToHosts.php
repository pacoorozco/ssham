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

namespace SSHAM\Console\Commands;

use Illuminate\Console\Command;
use ErrorException;
use Log;
use Crypt_RSA;
use Net_SFTP;
use File;
use Registry;
use SSHAM\Host;

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
    protected $description = 'Send SSH keys to hosts.';

    /**
     * Create a new command instance.
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
        $hosts = Host::where('synced', 0)->get();

        // Get SSHAM private key in order to connect to Hosts.
        $key = new Crypt_RSA();
        $key->loadKey(Registry::get('private_key'));

        foreach ($hosts as $host) {

            Log::debug('Trying to connect to ' . $host->getFullHostname());
            $sftp = new Net_SFTP($host->hostname, Registry::get('ssh_port'), Registry::get('ssh_timeout'));

            try {
                if (!$sftp->login($host->username, $key)) {

                    // Set last_error field on Host
                    // Set last_update on error status on Host

                    Log::warning('Can\'t auth on ' . $host->getFullHostname());
                    continue;
                }
            } catch (ErrorException $e) {

                // Set last_error field on Host
                // Set last_update on error status on Host

                Log::warning('Can\'t connect to ' . $host->getFullHostname() . ': ' . $e->getMessage());
                continue;
            }

            Log::debug('Connected successfully to ' . $host->getFullHostname());

            // Send remote_updater script to remote Host
            $fileContents = File::get('util/ssham-remote-updater.sh');
            $sftp->put(Registry::get('cmd_remote_updater'), $fileContents);
            $sftp->chmod(0700, Registry::get('cmd_remote_updater'));

            $sshKeys = $host->getSSHKeysForHost();
            $sshKeys[] = Registry::get('public_key');

            $sftp->put(Registry::get('ssham_file'), join(PHP_EOL, $sshKeys));
            $sftp->chmod(0600, Registry::get('ssham_file'));

            // Execute remote_updater script on remote Host
            $sftp->enableQuietMode();
            $command = Registry::get('cmd_remote_updater') . ' update '
                . ((Registry::get('mixed_mode') == '1') ? 'true ' : 'false ')
                . Registry::get('authorized_keys') . ' '
                . Registry::get('non_ssham_file') . ' '
                . Registry::get('ssham_file');

            Log::debug('Updated SSH authorized keys on ' . $host->getFullHostname());
            echo $sftp->exec($command);

            $sftp->disconnect();

            // Set synced status
        }

    }
}
