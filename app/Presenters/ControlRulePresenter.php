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
    public function actionWithIcon(): HtmlString
    {
        if ($this->model->action->is(ControlRuleAction::Allow)) {
            return new HtmlString('<i class="fa fa-lock-open"></i> ' . ControlRuleAction::getDescription($this->model->action));
        }

        return new HtmlString('<i class="fa fa-lock"></i> ' . ControlRuleAction::getDescription($this->model->action));
    }

    public function sourceWithLink(): HtmlString
    {
        $source = $this->model->source;
        return new HtmlString(
            sprintf('<a href="%s">%s</a> (%d %s)',
                route('keygroups.show', $source),
                $source->name,
                $source->keys()->count(),
                __('keygroup/model.keys')
            )
        );
    }

    public function targetWithLink(): HtmlString
    {
        $target = $this->model->target;
        return new HtmlString(
            sprintf('<a href="%s">%s</a> (%d %s)',
                route('hostgroups.show', $target),
                $target->name,
                $target->hosts()->count(),
                __('hostgroup/model.hosts')
            )
        );
    }
}
