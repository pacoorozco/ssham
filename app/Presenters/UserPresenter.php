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

    public function enabledAsBadge(): HtmlString
    {
        if ($this->model->enabled) {
            return new HtmlString('<span class="badge badge-success">'.__('general.enabled').'</span>');
        }

        return new HtmlString('<span class="badge badge-secondary">'.__('general.disabled').'</span>');
    }

    public function usernameWithDisabledBadge(): HtmlString
    {
        $badge = $this->enabledAsBadge();

        return $this->model->enabled
            ? new HtmlString($this->model->username)
            : new HtmlString($this->model->username.' '.$badge);
    }

    public function authenticationAsBadge(): HtmlString
    {
        return new HtmlString(
            $this->authTypeAsBadge().' '.$this->tokensCountAsBadge()
        );
    }

    public function tokensCountAsBadge(): HtmlString
    {
        $this->model->loadCount('tokens');
        if ($this->model->tokens_count > 0) {
            return new HtmlString('<span class="badge badge-pill badge-danger">tokens</span>');
        }

        return new HtmlString();
    }

    public function authTypeAsBadge(): HtmlString
    {
        if ($this->model->auth_type === AuthType::External) {
            return new HtmlString('<span class="badge badge-pill badge-info">external</span>');
        }

        return new HtmlString('<span class="badge badge-pill badge-secondary">'.$this->model->auth_type.'</span>');
    }

    public function createdAtForHumans(): string
    {
        return optional($this->model->created_at)->diffForHumans() ?? 'N/A';
    }

    public function role(): string
    {
        return $this->model->role->description;
    }
}
