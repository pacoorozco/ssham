<?php

use Illuminate\Database\Seeder;
use SSHAM\Usergroup;
use SSHAM\Hostgroup;

class UsergroupHostgroupPermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usergroup_hostgroup_permissions')->delete();

        $permissions = array(
            array(
                'usergroup' => 'developers',
                'hostgroup' => 'DEV_servers',
                'accesses' => 'allow',
                'description' => 'Developers can develop on development hosts',
            ),
            array(
                'usergroup' => 'operators',
                'hostgroup' => 'PRO_servers',
                'accesses' => 'allow',
                'description' => 'Operators can make its magic on production hosts',
            )
        );

        foreach ($permissions as $permission) {
            $usergroup = Usergroup::where('name', $permission['usergroup'])->firstOrFail();
            $hostgroup = Hostgroup::where('name', $permission['hostgroup'])->firstOrFail();

            $usergroup->permissions()->attach($hostgroup, [
                'accesses' => $permission['accesses'],
                'description' => $permission['description']
            ]);
        }
    }

}
