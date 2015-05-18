<?php

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
                'password' => 'admin',
                'role' => 'admin',
            )
        );

        foreach ($users as $userData) {
            $user = new User;
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->password = $userData['password'];
            $user->password_confirmation = $userData['password'];

            if (!$user->save()) {
                Log::info('Unable to create user ' . $user->username, (array) $user->errors());
                continue;
            }

            $role = Role::where('name', $userData['role'])->get()->first();
            $user->attachRole($role);

            Log::info('Created user ' . $user->username);
        }
    }

}
