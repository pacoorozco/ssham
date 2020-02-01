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

/**
 * Class Rule
 *
 * @package App
 *
 * @property string  $action
 * @property string  $name
 * @property boolean $enabled
 */
class Rule extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hostgroup_keygroup_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keygroup_id',
        'hostgroup_id',
        'action',
        'name',
        'enabled',
    ];

    /**
     * This is the relation with Hostgroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo('App\Hostgroup', 'hostgroup_keygroup_permissions');
    }

    /**
     * This is the relation with Keygroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo('App\Keygroup', 'hostgroup_keygroup_permissions');
    }
}
