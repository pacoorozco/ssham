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

use Laracodes\Presenter\Presenter;

class PersonalAccessTokenPresenter extends Presenter
{
    /** @var \App\Models\PersonalAccessToken */
    protected $model;

    public function getLastUsedDateString(): string
    {
        return (is_null($this->model->last_used_at))
            ? trans('user/personal_access_token.never_used')
            : trans('user/personal_access_token.last_used', [
                'time' => $this->model->last_used_at->diffForHumans(),
            ]);
    }
}
