<?php

namespace App\Models;

use Illuminate\Database\Eloquent\RelationNotFoundException;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    public function getLastUsedDateString(): string
    {
        return is_null($this->last_used_at)
            ? __('user/personal_access_token.never_used')
            : __('user/personal_access_token.last_used', ['time' => $this->last_used_at->diffForHumans()]);
    }

    public function relatedUser(): User
    {
        $user = $this->tokenable;
        if (!$user instanceof User) {
            throw new RelationNotFoundException('The related model is not an User instance.');
        }
        return $user;
    }
}
