<?php

namespace App\Policies;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalAccessTokenPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        //
    }

    public function delete(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        //
    }
}
