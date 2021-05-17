<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeUserPassword implements ShouldQueue
{
    use Dispatchable;

    private User $user;

    private string $newPassword;

    public function __construct(User $user, string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    public function handle(): User
    {
        $this->user->update([
            'password' => bcrypt($this->newPassword),
        ]);

        return $this->user;
    }
}
