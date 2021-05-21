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
use Illuminate\Support\Collection;
use Laracodes\Presenter\Traits\Presentable;

/**
 * Class ControlRule.
 *
 *
 * @property int $id
 * @property string $source
 * @property string $target
 * @property int $source_id
 * @property int $target_id
 * @property string $action
 * @property string $name
 * @property bool $enabled
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
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

    /**
     * Returns the Keygroup name used as source.
     *
     * @return string
     */
    public function getSourceAttribute(): string
    {
        return $this->getSourceObject()->name;
    }

    /**
     * Returns the Keygroup object used as source.
     *
     * @return \App\Models\Keygroup
     */
    public function getSourceObject(): Keygroup
    {
        return Keygroup::find($this->source_id);
    }

    /**
     * Returns the Hostgroup name used as target.
     *
     * @return string
     */
    public function getTargetAttribute(): string
    {
        return $this->getTargetObject()->name;
    }

    /**
     * Returns the Hostgroup object used as target.
     *
     * @return \App\Models\Hostgroup
     */
    public function getTargetObject(): Hostgroup
    {
        return Hostgroup::find($this->target_id);
    }

    /**
     * Returns a Collection of Rules with the specified 'source'.
     *
     * @param  int  $source_id
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findBySource(int $source_id): Collection
    {
        return ControlRule::where('source_id', $source_id)->get();
    }

    /**
     * Returns a Collection of Rules with the specified 'target'.
     *
     * @param  int  $target_id
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findByTarget(int $target_id): Collection
    {
        return ControlRule::where('target_id', $target_id)->get();
    }
}
