<?php

namespace SSHAM\Console\Commands;

use Illuminate\Console\Command;
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
        $hosts = Host::where('synced', 0)->get();
        foreach ($hosts as $host) {
            echo 'Connecting to ' . $host->getFullHostname() . "\n";

            $sftp = new \Net_SFTP($host->hostname, \Registry::get('ssh_port'), \Registry::get('ssh_timeout'));

            $key = new \Crypt_RSA();
            $key->loadKey(file_get_contents(\Registry::get('private_key')));

            try {
                if(! $sftp->login($host->username, $key)) {

                    // Set last_error field on Host
                    // Set last_update on error status on Host

                    echo "Error auth \n";
                    continue;
                }
            } catch (\ErrorException $e) {

                // Set last_error field on Host
                // Set last_update on error status on Host

                echo $e->getMessage() . "\n";
                continue;
            }

            // Send remote_updater script to remote Host
            $fileContents = \File::get('ssham-remote-updater.sh');
            $sftp->put(\Registry::get('cmd_remote_updater'), $fileContents);
            $sftp->chmod(0700, \Registry::get('cmd_remote_updater'));

            // Create a file containing all SSH keys on a temporary location
            // Send authorized_keys file to remote Host
            $sshKeys = $host->getSSHKeysForHost();
            $temp = '/tmp/lll.out';
            \File::delete($temp);
            foreach($sshKeys as $sshKey) {
                $rsa = new \Crypt_RSA();
                $rsa->loadKey($sshKey);
                $rsa->setPublicKey();

                $publickey = $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
                if (! $publickey) {
                    echo "Error with key '" . $sshKey . "' \n";
                    continue;
                }
                \File::append($temp, $publickey . "\n");
            }
            $fileContents = \File::get($temp);
            $sftp->put(\Registry::get('ssham_file'), $fileContents);
            $sftp->chmod(0600, \Registry::get('ssham_file'));

            // Executed remote_updater script on remote Host
            $sftp->enableQuietMode();
            $command = \Registry::get('cmd_remote_updater') .' update '
                . ((\Registry::get('mixed_mode') == '1') ? 'true ' : 'false ')
                . \Registry::get('authorized_keys') . ' '
                . \Registry::get('non_ssham_file') .' '
                . \Registry::get('ssham_file');

            echo "$command \n";
            echo $sftp->exec($command);

            $sftp->disconnect();

            // Set synced status
        }

    }
}
