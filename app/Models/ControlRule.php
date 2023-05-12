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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ControlRule.
 *
 * A Rule provides access from a KeyGroup to a HostGroup.
 * All the keys inside the KeyGroup will be able to access to the hosts in the HostGroup.
 * Only "Allow" action is used, because denial is by default.
 *
 * @property int $id
 * @property int $source_id
 * @property int $target_id
 * @property string|null $name
 * @property int $enabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Keygroup $source
 * @property-read Hostgroup $target
 */
class ControlRule extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'hostgroup_keygroup_permissions';

    protected $fillable = [
        'source_id',
        'target_id',
        'name',
        'enabled',
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
            ->setDescriptionForEvent(fn (string $eventName) => "Rule ':subject.name' was $eventName");
    }
}
