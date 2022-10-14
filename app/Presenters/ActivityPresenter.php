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

use App\Enums\ActivityStatus;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Laracodes\Presenter\Presenter;

class ActivityPresenter extends Presenter
{
    /** @var \App\Models\Activity */
    protected $model;

    public function activityAge(): HtmlString
    {
        return new HtmlString(optional($this->model->created_at)->diffForHumans());
    }

    public function causerUsername(): string
    {
        return optional($this->model->causer)->username ?? 'system';
    }

    public function statusBadge(): HtmlString
    {
        $status = $this->model->status;

        if ($status->is(ActivityStatus::Success)) {
            return Str::of('<p class="text-success">' . $status->description . '</p>')
                ->toHtmlString();
        }

        return Str::of($status->description ?? '')
            ->toHtmlString();
    }
}
