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
 * @link        https://github.com/pacoorozco/ssham
 */

return [
    /*
    |--------------------------------------------------------------------------
    | User Model Language Lines
    |--------------------------------------------------------------------------
    |
    */
    'private_key' => 'SSH Private key',
    'private_key_help' => 'Key used to access other hosts SSHAM will use this key to access to the remote hosts.',
    'public_key' => 'SSH Public key',
    'public_key_help' => 'Key used to distribute keys on remote hosts.',
    'ssh_port' => 'SSH remote port',
    'ssh_port_help' => 'Default SSH port to reach remote hosts.',
    'ssh_timeout' => 'Timeout',
    'ssh_timeout_help' => 'Time (seconds) to wait before a SSH connection is timed out.',
    'mixed_mode' => 'Enable mixed mode',
    'mixed_mode_help' => 'Mixed mode will merge, existing keys on remote hosts with keys managed by SSHAM. It allows to put non SSHAM keys that will not be removed by SSHAM.',
    'authorized_keys' => 'Authorized keys file',
    'authorized_keys_help' => 'Path where SSHAM will generate authorized keys file on remote hosts.',
    'ssham_file' => 'Authorized keys file for keys managed by SSHAM (only for mixed mode)',
    'ssham_file_help' => 'File on remote hosts where SSHAM will generate managed authorized keys.',
    'non_ssham_file' => 'Authorized keys file for keys non managed by SSHAM (only for mixed mode)',
    'non_ssham_file_help' => 'File on remote hosts where you can put authorized keys not managed by SSHAM.',
    'cmd_remote_updater' => 'Updater script remote path',
    'cmd_remote_updater_help' => 'Path on remote hosts where updater script will be copied.',
];
