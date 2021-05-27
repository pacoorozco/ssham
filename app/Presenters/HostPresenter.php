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
            return new HtmlString('<span class="badge badge-success">'.__('general.enabled').'</span>');
        }
        return new HtmlString('<span class="badge badge-secondary">'.__('general.disabled').'</span>');
    }

    public function createdAtForHumans(): string
    {
        return optional($this->model->created_at)->diffForHumans() ?? 'N/A';
    }

    public function pendingSyncAsBadge(): HtmlString
    {
        if ($this->model->synced) {
            return new HtmlString('<span class="badge badge-success">'.__('general.no').'</span>');
        }
        return new HtmlString('<span class="badge badge-warning">'.__('general.yes').'</span>');
    }

    public function statusCode(): string
    {
        return $this->model->status_code->description;
    }

    public function lastRotationForHumans(): string
    {
        return optional($this->model->last_rotation)->diffForHumans() ?? __('host/messages.never_rotated');
    }

    public function port(): string
    {
        return $this->model->getPortOrDefault();
    }

    public function authorizedKeysFile(): string
    {
        return $this->model->getAuthorizedKeysFileOrDefault();
    }
}
