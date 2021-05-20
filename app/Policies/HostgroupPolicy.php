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

namespace App\Policies;

use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostgroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Hostgroup $group): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Hostgroup $group): bool
    {
        return true;
    }

    public function delete(User $user, Hostgroup $group): bool
    {
        return true;
    }

    public function viewHosts(User $user, Hostgroup $group): bool
    {
        return true;
    }
}
