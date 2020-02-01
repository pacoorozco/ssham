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
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Key
 *
 * @package App
 *
 * @property string  $name
 * @property boolean $enabled
 * @property string  $type
 * @property string  $public
 * @property string  $fingerprint
 */
class Key extends Model implements Searchable
{
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
        'username' => 'string',
        'public' => 'string',
        'private' => 'string',
        'fingerprint' => 'string',
        'enabled' => 'boolean',
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
            $this->public = RsaSshKey::getPublicKey($key);
            $this->fingerprint = RsaSshKey::getPublicFingerprint($key);
        } catch (\Exception $exception) {
            return false;
        }

        return $skip_save ?: $this->save();
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
            $this->name,
            $url
        );
    }
}
