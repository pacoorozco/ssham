<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $currentUser): bool
    {
        return $currentUser->can(Permissions::ViewUsers, User::class);
    }

    public function view(User $currentUser, User $user): bool
    {
        return $currentUser->is($user)
            || $currentUser->can(Permissions::ViewUsers, $user);
    }

    public function create(User $currentUser): bool
    {
        return $currentUser->can(Permissions::EditUsers, User::class);
    }

    public function update(User $currentUser, User $user): bool
    {
        return $currentUser->is($user)
            || $currentUser->can(Permissions::EditUsers, $user);
    }

    public function delete(User $currentUser, User $user): bool
    {
        return $currentUser->isNot($user)
            && $currentUser->can(Permissions::DeleteUsers, $user);
    }
}
