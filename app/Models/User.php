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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Class User.
 *
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $enabled
 * @property string $auth_type
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

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
        'auth_type',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public static function createRandomPassword(): string
    {
        return Str::random(32);
    }

    public function setUsernameAttribute(string $value): void
    {
        $this->attributes['username'] = strtolower($value);
    }
}
