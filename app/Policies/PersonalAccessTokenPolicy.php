<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalAccessTokenPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, User $modelUser): bool
    {
        return $user->can('update', $modelUser);
    }

    public function create(User $user, User $modelUser): bool
    {
        return $user->can('update', $modelUser);
    }

    public function delete(User $user, User $modelUser): bool
    {
        return $user->can('update', $modelUser);
    }
}
