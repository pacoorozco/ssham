<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostPolicy
{
    use HandlesAuthorization;

    public function viewAny(): bool
    {
        return true;
    }

    public function view(): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('edit hosts');
    }

    public function update(User $user): bool
    {
        return $user->can('edit hosts');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete hosts');
    }

    public function viewGroups(): bool
    {
        return true;
    }
}
