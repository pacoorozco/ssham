<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM;

use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model
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
        return $this->belongsToMany('SSHAM\User');
    }

    public function hostgroups()
    {
        return $this->belongsToMany('SSHAM\Hostgroup', 'usergroup_hostgroup_permissions');
    }
}
