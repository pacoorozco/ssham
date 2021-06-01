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

namespace App\Models;

use App\Enums\ActivityStatus;
use App\Presenters\ActivityPresenter;
use Laracodes\Presenter\Traits\Presentable;

/**
 * Class Activity.
 *
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Database\Eloquent\Model $causer
 * @property-read \App\Enums\ActivityStatus $status
 */
class Activity extends \Spatie\Activitylog\Models\Activity
{
    use Presentable;

    protected string $presenter = ActivityPresenter::class;

    public function getStatusAttribute(): ActivityStatus
    {
        // There is a default value in order to avoid errors when database contains old messages
        // which doesn't have a value now.
        return ActivityStatus::coerce($this->getExtraProperty('status')) ?? ActivityStatus::Unknown();
    }
}
