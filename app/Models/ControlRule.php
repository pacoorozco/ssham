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

use App\Enums\ControlRuleAction;
use App\Presenters\ControlRulePresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laracodes\Presenter\Traits\Presentable;

class ControlRule extends Model
{
    use HasFactory;
    use Presentable;

    protected string $presenter = ControlRulePresenter::class;

    protected $table = 'hostgroup_keygroup_permissions';

    protected $fillable = [
        'source_id',
        'target_id',
        'action',
        'name',
        'enabled',
    ];
    protected $casts = [
        'action' => ControlRuleAction::class,
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Keygroup::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Hostgroup::class);
    }
}
