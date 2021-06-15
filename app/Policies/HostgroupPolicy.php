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

use App\Enums\Permissions;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostgroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ViewHosts, Hostgroup::class);
    }

    public function view(User $user, Hostgroup $model): bool
    {
        return $user->can(Permissions::ViewHosts, $model);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::EditHosts, Hostgroup::class);
    }

    public function update(User $user, Hostgroup $model): bool
    {
        return $user->can(Permissions::EditHosts, $model);
    }

    public function delete(User $user, Hostgroup $model): bool
    {
        return $user->can(Permissions::DeleteHosts, $model);
    }

    public function viewHosts(User $user): bool
    {
        return $user->can('viewAny', Host::class);
    }
}
