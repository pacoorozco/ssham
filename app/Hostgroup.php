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

class Hostgroup extends Model
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
        'description'
    ];

    /**
     * A Hostgroup is composed by Host (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hosts()
    {
        return $this->belongsToMany('SSHAM\Host');
    }

    public function usergroups()
    {
        return $this->belongsToMany('SSHAM\Usergroup', 'usergroup_hostgroup_permissions');
    }

}
