<?php

namespace App\Models;

use App\Presenters\PersonalAccessTokenPresenter;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * Class PersonalAccessToken.
 *
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property-read \Illuminate\Database\Eloquent\Model $tokenable
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use Presentable;

    protected string $presenter = PersonalAccessTokenPresenter::class;

    public function relatedUser(): User
    {
        $user = $this->tokenable;
        if (! $user instanceof User) {
            throw new RelationNotFoundException('The related model is not an User instance.');
        }

        return $user;
    }
}
