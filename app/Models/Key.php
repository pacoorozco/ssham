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

use App\Libs\RsaSshKey\RsaSshKey;
use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Key.
 *
 *
 * @property string $id
 * @property string $username
 * @property bool $enabled
 * @property string $type
 * @property string $public
 * @property string $private
 * @property string $fingerprint
 */
class Key extends Model implements Searchable
{
    use HasFactory;
    use UsesUUID;

    protected $table = 'keys';

    protected $fillable = [
        'username',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Attach the provided keys to the model and save it.
     *
     * It calculated the 'fingerprint' attribute also.
     *
     * @param  string  $public_key  - Provided public key
     * @param  ?string  $private_key  - Provided private key (nullable)
     *
     * @throws \Throwable
     */
    public function attachKeyAndSave(string $public_key, ?string $private_key = null): void
    {
        $this->attachKey($public_key, $private_key);
        $this->saveOrFail();
    }

    /**
     * Attach the provided keys to the model.
     *
     * It calculated the 'fingerprint' attribute also.
     *
     * @param  string  $public_key  - Provided public key
     * @param  ?string  $private_key  - Provided private key (nullable)
     *
     * @throws \App\Libs\RsaSshKey\InvalidInputException
     */
    public function attachKey(string $public_key, ?string $private_key = null): void
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
     * An Key belongs to many Keygroups (many-to-many).
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Keygroup::class);
    }

    public function setUsernameAttribute(string $value): void
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

    public function hasPrivateKey(): bool
    {
        return !empty($this->private);
    }
}
