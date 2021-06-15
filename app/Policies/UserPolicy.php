<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ViewUsers, User::class);
    }

    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id
            || $user->can(Permissions::ViewUsers, $model);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::EditUsers, User::class);
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id
            || $user->can(Permissions::EditUsers, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->id !== $model->id
            && $user->can(Permissions::DeleteUsers, $model);
    }
}
