<?php

use Illuminate\Database\Seeder;
use SSHAM\Host;

class HostsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hosts')->delete();

        $hosts = array(
            array(
                'hostname' => 'server_1',
                'username' => 'root',
                'type' => 'linux',
            ),
            array(
                'hostname' => 'server_2',
                'username' => 'root',
                'type' => 'linux',
            ),
            array(
                'hostname' => 'server_3',
                'username' => 'root',
                'type' => 'linux',
            ),
        );

        foreach ($hosts as $host) {
            Host::create($host);
        }
    }

}
