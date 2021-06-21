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

use App\Presenters\KeyPresenter;
use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Key.
 *
 * @property string $id
 * @property string $username
 * @property bool $enabled
 * @property string $type
 * @property string $public
 * @property string $private
 * @property string $fingerprint
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Keygroup[] $groups
 */
class Key extends Model implements Searchable
{
    use HasFactory;
    use UsesUUID;
    use Presentable;

    public string $searchableType = 'SSH Keys';
    protected string $presenter = KeyPresenter::class;
    protected $table = 'keys';
    protected $fillable = [
        'username',
        'public',
        'private',
        'enabled',
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];

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
