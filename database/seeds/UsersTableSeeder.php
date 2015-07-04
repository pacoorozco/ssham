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
                'username' => 'admin',
                'email' => 'admin@example.org',
                'auth_type' => 'local',
                'password' => bcrypt('admin'),
                'enabled' => true,
                'role' => 'admin',
            ),
            array(
                'username' => 'user',
                'email' => 'user@example.org',
                'auth_type' => 'local',
                'password' => bcrypt('user'),
                'enabled' => false,
                'role' => 'user',
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
