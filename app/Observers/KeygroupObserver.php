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

use App\Models\Activity;
use App\Models\Keygroup;

class KeygroupObserver
{
    public function created(Keygroup $keygroup): void
    {
        activity()
            ->performedOn($keygroup)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create key group '%s'.", $keygroup->name));
    }

    public function updated(Keygroup $keygroup): void
    {
        activity()
            ->performedOn($keygroup)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Update key group '%s'.", $keygroup->name));
    }

    public function deleted(Keygroup $keygroup): void
    {
        activity()
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Delete key group '%s'.", $keygroup->name));
    }

}
