<?php

namespace App\Policies;

use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeygroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Keygroup  $keygroup
     * @return mixed
     */
    public function view(User $user, Keygroup $keygroup)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Keygroup  $keygroup
     * @return mixed
     */
    public function update(User $user, Keygroup $keygroup)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Keygroup  $keygroup
     * @return mixed
     */
    public function delete(User $user, Keygroup $keygroup)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Keygroup  $keygroup
     * @return mixed
     */
    public function restore(User $user, Keygroup $keygroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Keygroup  $keygroup
     * @return mixed
     */
    public function forceDelete(User $user, Keygroup $keygroup)
    {
        //
    }
}
