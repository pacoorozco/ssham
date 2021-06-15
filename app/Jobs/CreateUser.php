<?php

namespace App\Jobs;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateUser implements ShouldQueue
{
    use Dispatchable;

    private string $username;

    private string $email;

    private string $password;

    private Roles $role;

    public function __construct(string $username, string $email, string $password, Roles $role)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function handle(): User
    {
        $user = User::create([
            'username' => $this->username,
            'password' => bcrypt($this->password),
            'email' => $this->email,
        ]);
        $user->assignRole($this->role->value);
        return $user;
    }
}
