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

use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class KeyPresenter extends Presenter
{
    /** @var \App\Models\Key */
    protected $model;

    public function createdAtForHumans(): string
    {
        return optional($this->model->created_at)->diffForHumans() ?? 'N/A';
    }

    public function updatedAtForHumans(): string
    {
        return optional($this->model->updated_at)->diffForHumans() ?? 'N/A';
    }

    public function nameWithDisabledBadge(): HtmlString
    {
        $badge = $this->enabledAsBadge();

        return $this->model->enabled
            ? new HtmlString($this->model->name)
            : new HtmlString($this->model->name.' '.$badge);
    }

    public function enabledAsBadge(): HtmlString
    {
        if ($this->model->enabled) {
            return new HtmlString('<span class="badge badge-success">'.trans('general.enabled').'</span>');
        }

        return new HtmlString('<span class="badge badge-secondary">'.trans('general.disabled').'</span>');
    }
}
