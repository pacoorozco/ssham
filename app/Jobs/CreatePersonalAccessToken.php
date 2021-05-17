<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePersonalAccessToken
{
    use Dispatchable;

    private User $user;

    private string $name;

    private array $abilities;

    public function __construct(User $user, string $name, array $abilities = ['*'])
    {
        $this->user = $user;
        $this->name = $name;
        $this->abilities = $abilities;
    }

    public function handle(): string
    {
        return $this->user->createToken($this->name, $this->abilities)->plainTextToken;
    }
}
