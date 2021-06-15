<?php

namespace App\Jobs;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateUser implements ShouldQueue
{
    use Dispatchable;

    private User $user;

    private string $email;

    private bool $enabled;

    private Roles $role;

    public function __construct(User $user, string $email, bool $enabled, Roles $role)
    {
        $this->user = $user;
        $this->email = $email;
        $this->enabled = $enabled;
        $this->role = $role;
    }

    public function handle(): User
    {
        $this->user->update([
            'email' => $this->email,
            'enabled' => $this->enabled,
        ]);
        $this->user->syncRoles($this->role->value);

        return $this->user;
    }
}
