<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use phpseclib\Crypt\RSA;

/**
 * User model, represents a SSHAM user.
 *
 * @property int    $id                      The object unique id.
 * @property string $username                The username that represents this user.
 * @property string $name                    The name of this user.
 * @property string $email                   The email address of this user.
 * @property string $password                Encrypted password of this user.
 * @property string $role                    The role of this user. Could be 'normal' or 'admin'.
 * @property string $public_key              The RSA public key of this user.
 * @property string $fingerprint             The fingerprint of $this->public_key.
 * @property bool   $active                  The status of this user.
 *
 */
class User extends Model implements AuthenticatableContract
{

    use Authenticatable;

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
        'name',
        'email',
        'role',
        'public_key',
        'fingerprint',
        'active',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'role',
        'password',
        'remember_token',
    ];

    /**
     * This method creates a RSA public / private key pair for the user.
     *
     * @return array
     */
    public function createRSAKeyPair()
    {
        // create a new RSA key pair
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $keyPair = $rsa->createKey();

        // save RSA public key
        $this->public_key = $keyPair['publickey'];

        // create a random name for RSA private key file
        $privateKey = str_random(32);
        \Storage::disk('local')->put($privateKey, $keyPair['privatekey']);

        // create a downloadable file, with a random name
        $fileEntry = new FileEntry();
        $fileEntry->filename = $privateKey;
        $fileEntry->mime = 'application/octet-stream';
        $fileEntry->original_filename = $this->username . '.rsa';

        $fileEntry->save();

        return array($keyPair['publickey'], $privateKey);
    }

    /**
     * An User belongs to many Usergroups (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usergroups()
    {
        return $this->belongsToMany('SSHAM\Usergroup');
    }

    /**
     * Method to check if user has administrator role.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Set the username User attribute to lowercase.
     *
     * @param  string $value
     */
    public function setUsernameAttribute(string $value)
    {
        $this->username = strtolower($value);
    }
}
