<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App;

use App\Libs\RsaSshKey\RsaSshKey;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User
 *
 * @package App
 *
 * @property string  $username
 * @property string  $name
 * @property string  $email
 * @property string  $password
 * @property boolean $enabled
 * @property string  $auth_type
 * @property string  $public_key
 * @property string  $fingerprint
 */
class User extends Authenticatable implements Searchable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'enabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'auth_type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'username' => 'string',
        'email' => 'string',
        'password' => 'string',
        'public_key' => 'string',
        'fingerprint' => 'string',
        'auth_type' => 'string',
        'enabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Attach the provided key as 'public_key' attribute.
     *
     * It calculated the 'fingerprint' attribute also.
     *
     * @param string $key       - Provided public key
     * @param bool   $skip_save - if true, the model is not saved (for testing)
     *
     * @return bool
     */
    public function attachPublicKey(string $key, bool $skip_save = false): bool
    {
        try {
            $this->public_key = RsaSshKey::getPublicKey($key);
            $this->fingerprint = RsaSshKey::getPublicFingerprint($key);
        } catch (\Exception $exception) {
            return false;
        }

        return $skip_save ?: $this->save();
    }

    public static function createRandomPassword(): string
    {
        return Str::random(32);
    }

    /**
     * An User belongs to many Usergroups (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usergroups()
    {
        return $this->belongsToMany('App\Usergroup');
    }

    /**
     * Set the username User attribute to lowercase.
     *
     * @param string $value
     */
    public function setUsernameAttribute(string $value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('users.show', $this->id);

        return new SearchResult(
            $this,
            $this->username,
            $url
        );
    }
}
