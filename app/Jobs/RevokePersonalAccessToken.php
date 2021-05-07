<?php

namespace App\Jobs;

use App\Models\PersonalAccessToken;

final class RevokePersonalAccessToken
{
    private PersonalAccessToken $token;

    public function __construct(PersonalAccessToken $token)
    {
        $this->token = $token;
    }

    public function handle(): bool
    {
        return $this->token->delete();
    }
}
