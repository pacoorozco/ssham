<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateUser implements ShouldQueue
{
    use Dispatchable;

    private User $user;

    private string $email;

    private bool $enabled;

    public function __construct(User $user, string $email, bool $enabled)
    {
        $this->user = $user;
        $this->email = $email;
        $this->enabled = $enabled;
    }

    public function handle(): User
    {
        $this->user->update([
            'email' => $this->email,
            'enabled' => $this->enabled,
        ]);

        return $this->user;
    }
}
