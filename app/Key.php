<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App;

use App\Libs\RsaSshKey\RsaSshKey;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Key
 *
 * @package App
 *
 * @property string  $id
 * @property string  $username
 * @property boolean $enabled
 * @property string  $type
 * @property string  $public
 * @property string  $private
 * @property string  $fingerprint
 */
class Key extends Model implements Searchable
{
    use UsesUuid;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'enabled'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'username' => 'string',
        'public' => 'string',
        'private' => 'string',
        'fingerprint' => 'string',
        'enabled' => 'boolean',
    ];

    /**
     * Attach the provided keys to the model and save it.
     *
     * It calculated the 'fingerprint' attribute also.
     *
     * @param string $public_key  - Provided public key
     * @param string $private_key - Provided private key (nullable)
     *
     * @throws \Throwable
     */
    public function attachKeyAndSave(string $public_key, string $private_key = null): void
    {
        $this->attachKey($public_key, $private_key);
        $this->saveOrFail();
    }

    /**
     * Attach the provided keys to the model.
     *
     * It calculated the 'fingerprint' attribute also.
     *
     * @param string $public_key  - Provided public key
     * @param string $private_key - Provided private key (nullable)
     *
     * @throws \App\Libs\RsaSshKey\InvalidInputException
     */
    public function attachKey(string $public_key, string $private_key = null): void
    {
        // In case of empty public_key, do nothing.
        if (empty($public_key)) {
            return;
        }

        $this->private = $private_key;
        $this->public = RsaSshKey::getPublicKey($public_key);
        $this->fingerprint = RsaSshKey::getPublicFingerprint($public_key);
    }

    /**
     * An Key belongs to many Keygroups (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Keygroup');
    }

    /**
     * Set the username Key attribute to lowercase.
     *
     * @param string $value
     */
    public function setUsernameAttribute(string $value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('keys.show', $this->id);

        return new SearchResult(
            $this,
            $this->username,
            $url
        );
    }
}
