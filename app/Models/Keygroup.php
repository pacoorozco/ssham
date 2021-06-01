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

use App\Presenters\KeygroupPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Keygroup
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Key[] $keys
 * @property-read \App\Models\ControlRule[] $rules
 *
 * @package App\Models
 */
class Keygroup extends Model implements Searchable
{
    use HasFactory;
    use Presentable;

    protected string $presenter = KeygroupPresenter::class;

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
