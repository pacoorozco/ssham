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

use App\Presenters\HostgroupPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Hostgroup.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Host[] $hosts
 * @property-read \App\Models\ControlRule[] $rules
 */
class Hostgroup extends Model implements Searchable
{
    use HasFactory;
    use Presentable;
    use LogsActivity;

    public string $searchableType = 'Host groups';

    protected string $presenter = HostgroupPresenter::class;

    protected $table = 'hostgroups';

    protected $fillable = [
        'name',
        'description',
    ];

    public function hosts(): BelongsToMany
    {
        return $this->belongsToMany(Host::class)->orderBy('hostname');
    }

    public function hostsCount(): int
    {
        return $this->hosts()->count();
    }

    public function getNumberOfRelatedRules(): int
    {
        return $this->rules()->count();
    }

    public function rules(): HasMany
    {
        return $this->hasMany(ControlRule::class, 'target_id');
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('hostgroups.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Host group ':subject.name' was {$eventName}");
    }
}
