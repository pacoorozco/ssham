<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        setting()->set([
            /*
             * Where I will put authorized keys on remote hosts?
             */
            'authorized_keys' => '.ssh/authorized_keys',

            /*
             * Bastion Host Private Key, used to access other hosts
             * SSHAM will use this key to access to remote hosts.
             * ATTENTION: It's very important to keep safe this key.
             */
            'private_key' => '-----BEGIN OPENSSH PRIVATE KEY-----
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
-----END OPENSSH PRIVATE KEY-----',

            /*
             * Bastion Host Public key, used to distribute on remote hosts
             */
            'public_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQDq+n4fg/aWUmHb3nPulsvHpIBnyjqqO/QwhBUwk0iESQmP8at8YF2w+9NEOVT2lQaGWzH7ZN484oMqWH1x+lwfu5xdi3Zjx3zZpWlgSKZ8V5bJ3ERoSobXY+epwR4+1xSet/qndXIRA3MWbEyh139epbR+dflQ59JwphU26lZtMqLwsgqacRCAUOzCabXe6Xh4bevZG7934Q5UPH9nqfqF/wq4L2LSkbGqL8xQveeIC9YFtf2ikhMhIfBBRQAQrp5dCcmMnIgfCw53atTorR9RK2gidOuBWn+jdXvkqydlIH0xidliegyI3vUYKd2ImpENg5XH29UB8K68uvrNWBp/qXzWPIdqCm6PM8tnjmSBqXoElS043B+JcyYkbZGZ9VDAitCrjWLB33X84GaadNSafsZB+KTUgcCYGQp1WxQi4REPjfGCz45QM9aaTy/BgsJdAOYtNPkQS+IBdjpddWOb7wPV784QaBlCESi2BqwajhDdgG7R6AymGs2EKYB9Aus= paco@cigro',

            /*
             * Time (seconds) to wait before a SSH connection ends (timed out)
             */
            'ssh_timeout' => '5',

            /*
             * Port to use as SSH
             */
            'ssh_port' => '22',

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
            'mixed_mode' => true,

            /*
             * This is the file that SSHAM will generate on remote hosts, this
             * file contains generated keys by SSHAM.
             */
            'ssham_file' => '.ssh/authorized_keys-ssham',

            /*
             * This is the file containing keys not managed by SSHAM. If you
             * want to use it, please put 'mixed_mode = 1'. Otherwise it's ignored.
             */
            'non_ssham_file' => '.ssh/authorized_keys-non-ssham',

            /*
             * Tools to use to deploy SSH
             */
            'cmd_remote_updater' => '.ssh/ssham-remote-updater.sh',
        ]);
    }
}
