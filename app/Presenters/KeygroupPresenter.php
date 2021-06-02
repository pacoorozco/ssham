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

class KeygroupPresenter extends Presenter
{
    /** @var \App\Models\Keygroup */
    protected $model;

    public function nameWithKeysCount(): HtmlString
    {
        return $this->linkableNameWithKeysCount(false);
    }

    public function linkableNameWithKeysCount(bool $linkable = true): HtmlString
    {
        $keysCount = '('.trans_choice('keygroup/messages.keys_count', $this->model->keys->count()).')';

        if (false === $linkable) {
            return new HtmlString(
                $this->model->name.' '.$keysCount
            );
        }

        return new HtmlString(
            '<a href="'.route('keygroups.show', $this->model).'">'.$this->model->name.'</a>'.' '.$keysCount
        );
    }

    public function rulesCount(): string
    {
        return trans_choice('rule/model.rules_count', $this->model->getNumberOfRelatedRules());
    }
}
