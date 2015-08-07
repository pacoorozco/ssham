<?php

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
        'private_key',
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
        'private_key',
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
