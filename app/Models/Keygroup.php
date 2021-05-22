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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Keygroup extends Model implements Searchable
{
    use HasFactory;

    protected $table = 'keygroups';

    protected $fillable = [
        'name',
        'description',
    ];

    public function keys(): BelongsToMany
    {
        return $this->belongsToMany(Key::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(ControlRule::class, 'source_id');
    }

    public function getNumberOfRelatedRules(): int
    {
        return $this->rules()->count();
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('keygroups.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
