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

namespace App\Observers;

use App\Enums\ActivityStatus;
use App\Libs\RsaSshKey\RsaSshKey;
use App\Models\Key;
use PacoOrozco\OpenSSH\PublicKey;

class KeyObserver
{
    public function created(Key $key): void
    {
        // Do not use ->performedOn() because Key uses UUID which are not compatible with it.
        activity()
            //->performedOn($key)
            ->withProperties(['status' => ActivityStatus::Success])
            ->log(sprintf("Create key '%s'.", $key->username));
    }

    public function updated(Key $key): void
    {
        // Do not use ->performedOn() because Key uses UUID which are not compatible with it.
        activity()
            //->performedOn($key)
            ->withProperties(['status' => ActivityStatus::Success])
            ->log(sprintf("Update key '%s'.", $key->username));
    }

    public function deleted(Key $key): void
    {
        activity()
            ->withProperties(['status' => ActivityStatus::Success])
            ->log(sprintf("Delete key '%s'.", $key->username));
    }

    public function saving(Key $key): void
    {
        $key->fingerprint = PublicKey::fromString($key->public)->getFingerPrint();
    }
}
