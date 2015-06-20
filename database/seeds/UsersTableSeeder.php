<?php

use Illuminate\Database\Seeder;
use SSHAM\Role;
use SSHAM\User;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $users = array(
            array(
                'name' => 'admin',
                'email' => 'admin@example.org',
                'type' => 'local',
                'password' => bcrypt('admin'),
                'role' => 'admins',
            )
        );

        foreach ($users as $userData) {
            $user = User::create(array_except($userData, array('role')));
            $role = Role::where('name', $userData['role'])->get()->first();
            $user->attachRole($role);
            Log::info('Created user ' . $user->username);
        }
    }

}
