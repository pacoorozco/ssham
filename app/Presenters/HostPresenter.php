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

use App\Models\Host;
use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class HostPresenter extends Presenter
{
    /** @var Host */
    protected $model;

    public function enabledAsBadge(): HtmlString
    {
        if ($this->model->enabled) {
            return new HtmlString('<span class="badge badge-success">'.trans('general.enabled').'</span>');
        }

        return new HtmlString('<span class="badge badge-secondary">'.trans('general.disabled').'</span>');
    }

    public function createdAtForHumans(): string
    {
        return optional($this->model->created_at)->diffForHumans() ?? 'N/A';
    }

    public function pendingSyncAsBadge(): HtmlString
    {
        if ($this->model->synced) {
            return new HtmlString('<span class="badge badge-success">'.trans('general.no').'</span>');
        }

        return new HtmlString('<span class="badge badge-warning">'.trans('general.yes').'</span>');
    }

    public function statusCode(): string
    {
        return $this->model->status_code->description ?? 'N/A';
    }

    public function lastRotationForHumans(): string
    {
        return optional($this->model->last_rotation)->diffForHumans() ?? trans('host/messages.never_rotated');
    }

    public function port(): string
    {
        return sprintf('%d', $this->model->port);
    }

    public function portDefaultSetting(): string
    {
        return sprintf('%d', $this->model->portDefaultSetting());
    }

    public function authorizedKeysFileDefaultSetting(): string
    {
        return $this->model->authorizedKeysFileDefaultSetting();
    }
}
