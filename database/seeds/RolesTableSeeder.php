<?php

use Illuminate\Database\Seeder;
use SSHAM\Permission;
use SSHAM\Role;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        $roles = array(
            array(
                'name' => 'admins',
                'description' => 'Administrative user',
                'abilities' => array(
                    'manage_users', 'manage_hosts', 'manage_permissions', 'manage_admins', 'can_access'
                ),
            ),
            array(
                'name' => 'users',
                'description' => 'Regular user',
                'abilities' => array(),
            )
        );

        foreach ($roles as $roleData) {
            $role = new Role;
            $role->name = $roleData['name'];
            $role->description = $roleData['description'];
            $role->save();

            // Adds permission
            foreach ($roleData['abilities'] as $ability) {
                $permission = Permission::where('name', $ability)->get()->first();
                $role->attachPermission($permission);
            }
        }
    }

}
