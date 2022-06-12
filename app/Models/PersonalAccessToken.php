<?php

namespace App\Models;

use App\Presenters\PersonalAccessTokenPresenter;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class PersonalAccessToken.
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property array|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model $tokenable
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use Presentable;
    use LogsActivity;

    protected string $presenter = PersonalAccessTokenPresenter::class;

    public function relatedUser(): User
    {
        $user = $this->tokenable;
        if (!$user instanceof User) {
            throw new RelationNotFoundException('The related model is not an User instance.');
        }

        return $user;
    }

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        $relatedUser = $this->relatedUser()?->username ?? 'unknown';

        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Access token ':subject.name' for user '{$relatedUser}' was {$eventName}");
    }
}
