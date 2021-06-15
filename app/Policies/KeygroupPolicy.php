<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeygroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ViewKeys, Keygroup::class);
    }

    public function view(User $user, Keygroup $model): bool
    {
        return $user->can(Permissions::ViewKeys, $model);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::EditKeys, Keygroup::class);
    }

    public function update(User $user, Keygroup $model): bool
    {
        return $user->can(Permissions::EditKeys, $model);
    }

    public function delete(User $user, Keygroup $model): bool
    {
        return $user->can(Permissions::DeleteKeys, $model);
    }
}
