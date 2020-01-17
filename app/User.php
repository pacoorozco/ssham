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

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpseclib\Crypt\RSA;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Zizaco\Entrust\Traits\EntrustUserTrait;

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
        'public_key',
        'fingerprint',
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

    public function createRSAKeyPair()
    {
        // create a new RSA key pair
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $keyPair = $rsa->createKey();

        // save RSA public key
        $this->public_key = $keyPair['publickey'];

        // create a random name for RSA private key file
        $privateKeyFileName = Str::random(32);
        Storage::disk('local')->put($privateKeyFileName, $keyPair['privatekey']);

        // create a downloadable file, with a random name
        $fileEntry = new FileEntry();
        $fileEntry->filename = $privateKeyFileName;
        $fileEntry->mime = 'application/octet-stream';
        $fileEntry->original_filename = $this->username . '.rsa';
        $fileEntry->save();

        return array($keyPair['publickey'], $privateKeyFileName);
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
