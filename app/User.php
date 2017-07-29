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
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract
{

    use Authenticatable,
        EntrustUserTrait;

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
        'public_key',
        'fingerprint',
        'enabled'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'auth_type',
        'password',
        'remember_token'
    ];

    public function createRSAKeyPair()
    {
        // create a new RSA key pair
        $rsa = new \Crypt_RSA();
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
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
}
