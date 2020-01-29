<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Usergroup
 *
 * @package App
 *
 * @property string $name
 * @property string $description
 */
class Usergroup extends Model implements Searchable
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usergroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * An Usergroup is composed by many User (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * TODO: Document it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hostgroups()
    {
        return $this->belongsToMany('App\Hostgroup', 'hostgroup_usergroup_permissions');
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('usergroups.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
