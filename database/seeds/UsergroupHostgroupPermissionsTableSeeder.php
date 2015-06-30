<?php

use Illuminate\Database\Seeder;
use SSHAM\Usergroup;
use SSHAM\Hostgroup;
use SSHAM\Rule;

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
                'permission' => 'allow',
                'description' => 'Developers can develop on development hosts',
            ),
            array(
                'usergroup' => 'operators',
                'hostgroup' => 'PRO_servers',
                'permission' => 'allow',
                'description' => 'Operators can make its magic on production hosts',
            )
        );

        foreach ($permissions as $permission) {
            $usergroup = Usergroup::where('name', $permission['usergroup'])->firstOrFail();
            $hostgroup = Hostgroup::where('name', $permission['hostgroup'])->firstOrFail();

            $rule = new Rule;
            $rule->usergroup_id = $usergroup->id;
            $rule->hostgroup_id = $hostgroup->id;
            $rule->permission = $permission['permission'];
            $rule->description = $permission['description'];
            $rule->save();
        }
    }

}
