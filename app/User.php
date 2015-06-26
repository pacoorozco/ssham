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
        'name',
        'publickey',
        'fingerprint',
        'active'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'type', 
        'password',
        'remember_token'
    ];

    public function createRSAKeyPair()
    {
        $rsa = new \Crypt_RSA();
        $keyPair = $rsa->createKey();

        $this->publickey = $keyPair['publickey'];

        $privateKey = str_random();
        Storage::disk('local')->put($privateKey, $keyPair['privatekey']);

        $fileEntry = new FileEntry();
        $fileEntry->filename = $privateKey;
        $fileEntry->mime = 'application/octet-stream';
        $fileEntry->original_filename = $this->name . '.rsa';
        $fileEntry->save();

        return $privateKey;
    }

    public function groups()
    {
        return $this->belongsToMany('SSHAM\Usergroup');
    }
}
