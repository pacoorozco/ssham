<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2022 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2022 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Actions;

use App\Models\Host;

class CreateHostAction
{
    public function __invoke(
        string $hostname,
        string $username,
        bool $enabled,
        int|null $port,
        string|null $authorizedKeysFile,
        array $groups = []
    ): Host {
        /* @var Host $host */
        $host = Host::create([
            'hostname' => $hostname,
            'username' => $username,
            'port' => $port,
            'enabled' => $enabled,
            'authorized_keys_file' => $authorizedKeysFile,
        ]);

        $host->groups()->sync($groups);

        return $host;
    }
}
