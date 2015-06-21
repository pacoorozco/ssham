<?php

use Illuminate\Database\Seeder;
use SSHAM\Hostgroup;

class HostgroupsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hostgroups')->delete();

        $hostgroups = array(
            array(
                'name' => 'PRO_servers',
                'description' => 'Production Servers'
            ),
            array(
                'name' => 'DEV_servers',
                'description' => 'Developement Servers'
            )
        );

        foreach ($hostgroups as $group) {
            Hostgroup::create($group);
        }
    }

}
