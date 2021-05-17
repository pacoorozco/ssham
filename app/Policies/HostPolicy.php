<?php

namespace App\Policies;

use App\Models\Host;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Host $host): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Host $host): bool
    {
        return true;
    }

    public function delete(User $user, Host $host): bool
    {
        return true;
    }
}
