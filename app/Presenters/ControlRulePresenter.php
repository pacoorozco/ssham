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

use App\Enums\ControlRuleAction;
use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class ControlRulePresenter extends Presenter
{
    /** @var \App\Models\ControlRule */
    protected $model;

    public function actionWithIcon(): HtmlString
    {
        $action = $this->model->action;
        if ($action->is(ControlRuleAction::Allow)) {
            return new HtmlString('<i class="fa fa-lock-open"></i> '.$action->description);
        }

        return new HtmlString('<i class="fa fa-lock"></i> '.$action->description);
    }
}
