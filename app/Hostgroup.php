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

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Hostgroup.
 *
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 */
class Hostgroup extends Model implements Searchable
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hostgroups';

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
     * A Hostgroup is composed by Host (many-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hosts()
    {
        return $this->belongsToMany('App\Host');
    }

    /**
     *  Returns the number of Rules where this Hostgroup is present.
     *
     * @return int
     */
    public function getNumberOfRelatedRules(): int
    {
        return $this->getRelatedRules()->count();
    }

    /**
     * Returns a Collection of Rules where this Hostgroup is present.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRelatedRules()
    {
        return ControlRule::findByTarget($this->id);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('hostgroups.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
