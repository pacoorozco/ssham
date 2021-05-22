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

namespace App\Listeners;

use App\Enums\ActivityStatus;
use App\Events\PrivateKeyWasDownloaded;

class RemovePrivateKey
{
    public function handle(PrivateKeyWasDownloaded $event): void
    {
        $key = $event->getKey();
        $key->private = null;
        $key->save();

        // Do not use ->performedOn() because Key uses UUID which are not compatible with it.
        activity()
            //->performedOn($key)
            ->withProperties(['status' => ActivityStatus::Success])
            ->log(sprintf("Private key was removed from key '%s'.", $key->username));
    }
}
