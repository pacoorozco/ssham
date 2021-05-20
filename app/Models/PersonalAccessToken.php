<?php

namespace App\Models;

use App\Presenters\PersonalAccessTokenPresenter;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use Presentable;

    protected string $presenter = PersonalAccessTokenPresenter::class;

    public function relatedUser(): User
    {
        $user = $this->tokenable;
        if (!$user instanceof User) {
            throw new RelationNotFoundException('The related model is not an User instance.');
        }

        return $user;
    }
}
