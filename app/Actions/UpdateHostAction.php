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

class UpdateHostAction
{
    public function __invoke(
        Host $host,
        array $options,
    ): Host {
        $host->update([
            'port' => $options['port'] ?? $host->port,
            'enabled' => $options['enabled'] ?? $host->enabled,
            'authorized_keys_file' => $options['authorized_keys_file'] ?? $host->authorized_keys_file,
        ]);

        $host->groups()->sync($options['groups'] ?? []);

        return $host->refresh();
    }
}
