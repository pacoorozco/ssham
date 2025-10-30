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

use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;

class CreateRuleAction
{
    public function __invoke(
        string $name,
        Keygroup $source,
        Hostgroup $target,
    ): ControlRule {
        return ControlRule::create([
            'name' => $name,
            'source_id' => $source->id,
            'target_id' => $target->id,
        ]);
    }
}
