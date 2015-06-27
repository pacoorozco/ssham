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
                'name' => 'developers',
                'description' => 'Group of awesome developers'
            ),
            array(
                'name' => 'operators',
                'description' => 'Group of incredible operators'
            )
        );

        foreach ($usergroups as $group) {
            Usergroup::create($group);
        }
    }

}
