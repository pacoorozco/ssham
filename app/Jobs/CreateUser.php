<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateUser implements ShouldQueue
{
    use Dispatchable;

    private string $username;

    private string $email;

    private string $password;

    public function __construct(string $username, string $email, string $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function handle(): User
    {
        return User::create([
            'username' => $this->username,
            'password' => bcrypt($this->password),
            'email' => $this->email,
        ]);
    }
}
