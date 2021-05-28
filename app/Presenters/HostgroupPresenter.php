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

namespace App\Presenters;

use App\Models\Hostgroup;
use Laracodes\Presenter\Presenter;

class HostgroupPresenter extends Presenter
{
    /** @var Hostgroup */
    protected $model;

    public function rulesCount(): string
    {
        return trans_choice('rule/model.rules_count', $this->model->getNumberOfRelatedRules(), [
            'value' => $this->model->getNumberOfRelatedRules(),
        ]);
    }
}
