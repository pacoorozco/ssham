<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Events;

use App\Models\Key;
use Illuminate\Foundation\Events\Dispatchable;

class PrivateKeyWasDownloaded
{
    use Dispatchable;

    private Key $key;

    public function getKey(): Key
    {
        return $this->key;
    }

    public function __construct(Key $key)
    {
        $this->key = $key;
    }
}
