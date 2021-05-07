<?php

namespace App\Jobs;

use App\Http\Requests\PersonalAccessTokenRequest;
use App\Models\User;

final class CreatePersonalAccessToken
{
    private User $user;

    private string $name;

    private array $abilities;

    public function __construct(User $user, string $name, array $abilities = ['*'])
    {
        $this->user = $user;
        $this->name = $name;
        $this->abilities = $abilities;
    }

    public static function fromRequest(PersonalAccessTokenRequest $request): self
    {
        return new CreatePersonalAccessToken(
            $request->requestedUser(),
            $request->name(),
        );
    }

    public function handle(): string
    {
        return $this->user->createToken($this->name, $this->abilities)->plainTextToken;
    }
}
