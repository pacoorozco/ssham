<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('SettingsTableSeeder');
        $this->call('PermissionsTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('UsergroupsTableSeeder');
        $this->call('HostsTableSeeder');
        $this->call('HostgroupsTableSeeder');
        $this->call('UsergroupHostgroupPermissionsTableSeeder');
    }

}
