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
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Keygroup.
 *
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 */
class Keygroup extends Model implements Searchable
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keygroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];

    /**
     * An Keygroup is composed by many Keys (many-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keys()
    {
        return $this->belongsToMany(Key::class);
    }

    /**
     * Returns the number of Rules where this Keygroup is present.
     *
     * @return int
     */
    public function getNumberOfRelatedRules(): int
    {
        return $this->getRelatedRules()->count();
    }

    /**
     * Returns a Collection of Rules where this Keygroup is present.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRelatedRules()
    {
        return ControlRule::findBySource($this->id);
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
