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
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ControlRule.
 *
 * @property int $id
 * @property int $source_id
 * @property int $target_id
 * @property \App\Enums\ControlRuleAction $action
 * @property string|null $name
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Keygroup $source
 * @property-read \App\Models\Hostgroup $target
 */
class ControlRule extends Model
{
    use HasFactory;
    use Presentable;
    use LogsActivity;

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

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Rule ':subject.name' was {$eventName}");
    }
}
