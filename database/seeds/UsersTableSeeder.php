<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

use Illuminate\Database\Seeder;
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

        // Define all users that will be created.
        $users = array(
            array(
                'username'    => 'admin',
                'name'        => 'Administrator',
                'email'       => 'admin@example.org',
                'password'    => bcrypt('admin'),
                'public_key'  => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDDG8SY2RVR5i2Ur1uHzuKQ6MDNWPdQWMKCEdmA4IUvc2Ne0SccpXDjScfzfDKeJALdeUu+xnw2Y7JkRetyJ40AbQhXJTzwNq6Var+MXEV0wi2qJfyeQ39V4f4Vil0OFfgismcrMGNrHZa8KygglsOC7KzRfw4V3lmRtXQsGQFhChkJonphuXmJuuezxrszOENg9Fj2MQRVaT0nyp/KC77U9e75UCBo+M+IBOfCWSFYwLPvbp6Gtqb2M9b1zI48/E1v5fEek4x7ohoBnAOEWx1icE0Wz2bKfbBxRkvg+RKFzW6syTDXNihYfCoYUb+Y2E1gqp0burWRec9zqoY9szet admin@ssham',
                'fingerprint' => '49:97:ee:68:89:7e:cc:ce:60:e7:c9:c8:fe:1d:c0:d3',
                'active'      => true,
                'role'        => 'admin',
            ),
            array(
                'username'    => 'user',
                'name'        => 'Normal user',
                'email'       => 'user@example.org',
                'password'    => bcrypt('user'),
                'public_key'  => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDEHofIHQHLJMmGZpQhgBEzKnDfVL7cvY/kmrEgG+cfqfAEn3LRVQMj8N1MtzxVi04eRd/327ea9eNLjLaDXP124ReQ391SzS4tJLlxD6/lyrD9P4d3RUiTIE+sVLPDqaZXYo0NUn+aToZ+7mfoRDWG6xl1wAujw5y3l5aiuhAfpxDHANO4aYt2hGKuh1cZ502On5BggMq/XBuoYY7ZczaH4E4UyuoivDf9h3FUh2pXmtP3CrWdePsUN8d6t6hiRbstG5/oPRXzg10HI6r4C3H7xVGC88tXAd4LQ7+NK1lCzsjC7VlnkmSqfl1YLLf1qWFkJNv1/p6Gf8DRX3FW9v0L user@ssham',
                'fingerprint' => '9d:5c:d0:95:09:e2:22:45:ca:0a:20:c9:13:05:63:1c',
                'active'      => false,
                'role'        => 'user',
            )
        );

        // Create users into database
        foreach ($users as $userData) {
            $user = User::create(array_except($userData, array('role')));
            Log::info('Created user ' . $user->username);
        }
    }

}
