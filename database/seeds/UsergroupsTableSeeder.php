<?php

use Illuminate\Database\Seeder;
use SSHAM\Usergroup;
      
class UsergroupsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usergroups')->delete();

        $usergroups = array(
            array(
                'name' => 'group_1',
                'description' => 'Group One'
            ),
            array(
                'name' => 'group_2',
                'description' => 'Group Two'
            )
        );

        foreach ($usergroups as $group) {
            Usergroup::create($group);
        }
    }

}
