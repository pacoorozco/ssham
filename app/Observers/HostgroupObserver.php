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
use App\Models\Hostgroup;

class HostgroupObserver
{
    public function created(Hostgroup $hostgroup): void
    {
        activity()
            ->performedOn($hostgroup)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create host group '%s'.", $hostgroup->name));
    }

    public function updated(Hostgroup $hostgroup): void
    {
        activity()
            ->performedOn($hostgroup)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Update host group '%s'.", $hostgroup->name));
    }

    public function deleted(Hostgroup $hostgroup): void
    {
        activity()
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Delete host group '%s'.", $hostgroup->name));
    }
}
