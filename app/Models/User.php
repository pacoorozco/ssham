<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Models;

use App\Enums\AuthType;
use App\Enums\Roles;
use App\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laracodes\Presenter\Traits\Presentable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User.
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $enabled
 * @property string $auth_type
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PersonalAccessToken[] $tokens
 */
final class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use Presentable;
    use HasRoles;
    use LogsActivity;

    protected string $presenter = UserPresenter::class;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'email_verified_at' => 'datetime',
        'auth_type' => AuthType::class,
    ];

    protected $attributes = [
        'auth_type' => AuthType::Local,
    ];

    protected function username(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    public function hasTokens(): bool
    {
        return $this->tokens()->exists();
    }

    public function tokensCount(): int
    {
        return $this->tokens()->count();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Roles::SuperAdmin);
    }

    public function getRoleAttribute(): Roles
    {
        $roleName = $this->getRoleNames()->first();

        return Roles::fromValue($roleName);
    }

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['email'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "User ':subject.username' was {$eventName}");
    }
}
