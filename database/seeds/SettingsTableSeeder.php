<?php

use Illuminate\Database\Seeder;
use Torann\Registry\Facades\Registry;

class SettingsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Registry::flush();

        $settings = array(
            /*
             * Internal APP information, couldn't be setted
             */
            'app_name' => 'SSHAM',
            'app_version' => '2.0',

            /*
             * Where I will put authorized keys on remote hosts?
             */
            'authorized_keys' => '~/.ssh/authorized_keys',

            /*
             * Bastion Host Private Key, used to access other hosts
             * SSHAM will use this key to access to remote hosts.
             * ATTENTION: It's very important to keep safe this key.
             */
            'private_key' => '/root/.ssh/id_rsa',

            /*
             * Bastion Host Public key, used to distribute on remote hosts
             */
            'public_key' => '/root/.ssh/id_rsa.pub',

            /*
             * Where must I put temporary files?
             */
            'temp_dir' => '/tmp',

            /*
             * Time (seconds) to wait before a SSH connection ends (timed out)
             */
            'ssh_timeout' => '10',

            /*
             * SSHAM will generate an authorized_keys for every remote host,
             * if there is any previous keys, SSHAM will remove it.
             * The ONLY way to deal with keys will be SSHAM, unless you use
             * MIXED MODE, se below.
             *
             * In MIXED MODE, SSHAM will merge, existing keys on remote hosts
             * with keys managed by SSHAM. It allows to put non SSHAM keys that
             * will not be removed by SSHAM.
             */
            'mixed_mode' => '1',

            /*
             * This is the file that SSHAM will generate on remote hosts, this
             * file contains generated keys by SSHAM.
             */
            'ssham_file' => '~/.ssh/authorized_keys-ssham',

            /*
             * This is the file containing keys not managed by SSHAM. If you
             * want to use it, please put 'mixed_mode = 1'. Otherwise it's ignored.
             */
            'non_ssham_file' => '~/.ssh/authorized_keys-non-ssham',

            /*
             * Authentication Type
             *  - auth_type = local - For database builtin authentication
             *  - auth_type = ldap  - For LDAP authentication
             */
            'auth_type' => 'local',

            /*
             * LDAP Settings
             */
            'ldap_host' => 'ldaps://hostname',
            'ldap_dn' => 'cn=%s,ou=users,dc=upc,dc=edu',

            /*
             * Tools to use to deply SSH
             */
            'cmd_keygen' => '/usr/bin/ssh-keygen',
            'cmd_remote_updater' => 'ssham-remote-updater.sh',
        );

        Registry::store($settings);
    }

}
