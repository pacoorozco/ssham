<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteUser
{
    use Dispatchable;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): bool
    {
        // delete() can return null while we want to return boolean.
        return $this->user->delete() ?? false;
    }
}
