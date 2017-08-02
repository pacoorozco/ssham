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

class Rule extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usergroup_hostgroup_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usergroup_id',
        'hostgroup_id',
        'action',
        'name'
    ];

    /**
     * Set Rule status
     *     1, enabled, true = Rule is enabled
     *     0, disabled, false = Rule is disabled
     *
     * @param $status
     */
    public function setStatus($status)
    {
        $status = ($status === 1 || $status == 'enabled' || $status === true) ? 1 : 0;
        $this->enabled = $status;
    }

    /**
     * Set Rule action
     *     Values could be 'allow' or 'deny'
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Toggle Rule action field. If is 'allow', set 'deny', and otherwise
     */
    public function toggleAction()
    {
        $action = ($this->action == 'allow') ? 'deny' : 'allow';
        $this->setAction($action);
    }

}
