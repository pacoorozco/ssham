<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ViewHosts, Host::class);
    }

    public function view(User $user, Host $model): bool
    {
        return $user->can(Permissions::ViewHosts, $model);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::EditHosts, Host::class);
    }

    public function update(User $user, Host $model): bool
    {
        return $user->can(Permissions::EditHosts, $model);
    }

    public function delete(User $user, Host $model): bool
    {
        return $user->can(Permissions::DeleteHosts, $model);
    }

    public function viewGroups(User $user): bool
    {
        return $user->can('viewAny', Hostgroup::class);
    }
}
