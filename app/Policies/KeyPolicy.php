<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\Key;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ViewKeys->value, Key::class);
    }

    public function view(User $user, Key $model): bool
    {
        return $user->can(Permissions::ViewKeys->value, $model);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::EditKeys->value, Key::class);
    }

    public function update(User $user, Key $model): bool
    {
        return $user->can(Permissions::EditKeys->value, $model);
    }

    public function delete(User $user, Key $model): bool
    {
        return $user->can(Permissions::DeleteKeys->value, $model);
    }
}
