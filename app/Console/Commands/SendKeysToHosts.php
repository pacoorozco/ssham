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

            \Config::set('remote.connections.runtime.host', $host->hostname);
            \Config::set('remote.connections.runtime.port', '22');
            \Config::set('remote.connections.runtime.username', $host->username);
            \Config::set('remote.connections.runtime.key', \Registry::get('private_key'));
            \Config::set('remote.connections.runtime.keyphrase', '');

            try {
            \SSH::into('runtime')->run(array(
                'touch hola',
            ));
            } catch(\ErrorException $e) {
                echo "Adios \n";
            }
        }

    }
}
