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

use App\Models\Activity;
use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class ActivityPresenter extends Presenter
{
    public function activityAge(): HtmlString
    {
        return new HtmlString($this->model->created_at->diffForHumans());
    }

    public function causerUsername(): string
    {
        return (is_null($this->model->causer)) ? 'system' : $this->model->causer->username;
    }

    public function statusBadge(): HtmlString
    {
        switch ($this->model->status) {
            case Activity::STATUS_SUCCESS:
                return new HtmlString('<p class="text-success">'.__('activity/model.statuses.success').'</p>');
            case Activity::STATUS_FAIL:
                return new HtmlString(__('activity/model.statuses.failed'));

        }

        return new HtmlString(__('activity/model.statuses.unknown'));
    }
}
