<?php

namespace App\Jobs;

use App\Models\PersonalAccessToken;
use Illuminate\Foundation\Bus\Dispatchable;

class RevokePersonalAccessToken
{
    use Dispatchable;

    private PersonalAccessToken $token;

    public function __construct(PersonalAccessToken $token)
    {
        $this->token = $token;
    }

    public function handle(): bool
    {
        // delete() can return null while we want to return boolean.
        return $this->token->delete() ?? false;
    }
}
