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

return [
    /*
    |--------------------------------------------------------------------------
    | User Model Language Lines
    |--------------------------------------------------------------------------
    |
    */
    'private_key'             => 'Private key',
    'private_key_help'        => 'SHAM will use this key to connect to the remote hosts.',
    'public_key'              => 'Public key',
    'public_key_help'         => 'Remote hosts will identify SSHAM with this key. It should have been distributed to the remote hosts to allow access without password.',
    'ssh_port'                => 'SSH remote port',
    'ssh_port_help'           => 'Port to reach remote hosts by SSH protocol.',
    'ssh_timeout'             => 'Timeout',
    'ssh_timeout_help'        => 'Time (seconds) to wait before a SSH connection is timed out.',
    'mixed_mode'              => 'Hybrid mode',
    'mixed_mode_help'         => 'If hybrid mode is <b>enabled</b>, the remote hosts will accept other access keys <b>non-managed</b> by SSHAM.',
    'authorized_keys'         => 'authorized_keys file remote path',
    'authorized_keys_help'    => 'Path of the <code>authorized_keys</code> file which contains the SSH keys that can be used for logging into the remote hosts.',
    'ssham_file'              => 'File to keep the managed keys',
    'ssham_file_help'         => 'File on remote hosts where SSHAM will generate the managed access keys.',
    'non_ssham_file'          => 'File containing keys non-managed by SSHAM',
    'non_ssham_file_help'     => 'File on remote hosts where you maintain access keys non-managed by SSHAM.',
    'cmd_remote_updater'      => 'Updater script remote path',
    'cmd_remote_updater_help' => 'Path on remote hosts where the <code>ssham-remote-updater.sh</code> will be copied.',
];
