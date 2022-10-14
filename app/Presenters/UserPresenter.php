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

use App\Enums\AuthType;
use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class UserPresenter extends Presenter
{
    /** @var \App\Models\User */
    protected $model;

    public function usernameWithDisabledBadge(): HtmlString
    {
        return $this->model->enabled
            ? new HtmlString($this->model->username)
            : new HtmlString($this->model->username.' '.$this->enabledAsBadge());
    }

    public function enabledAsBadge(): HtmlString
    {
        if ($this->model->enabled) {
            return new HtmlString('<span class="badge badge-success">'.trans('general.enabled').'</span>');
        }

        return new HtmlString('<span class="badge badge-secondary">'.trans('general.disabled').'</span>');
    }

    public function authenticationAsBadge(): HtmlString
    {
        return new HtmlString(
            $this->authTypeAsBadge().' '.$this->tokensCountAsBadge()
        );
    }

    public function authTypeAsBadge(): HtmlString
    {
        return new HtmlString('<span class="badge badge-pill badge-secondary">'.AuthType::Local.'</span>');
    }

    public function tokensCountAsBadge(): HtmlString
    {
        if ($this->model->tokensCount() > 0) {
            return new HtmlString('<span class="badge badge-pill badge-danger">tokens</span>');
        }

        return new HtmlString();
    }

    public function createdAtForHumans(): string
    {
        return optional($this->model->created_at)->diffForHumans() ?? 'N/A';
    }

    public function role(): string
    {
        return $this->model->role->description ?? 'N/A';
    }
}
