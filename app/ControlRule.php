<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ControlRule.
 *
 *
 * @property int     $id
 * @property string  $source
 * @property string  $target
 * @property int     $source_id
 * @property int     $target_id
 * @property string  $action
 * @property string  $name
 * @property bool $enabled
 */
class ControlRule extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hostgroup_keygroup_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_id',
        'target_id',
        'action',
        'name',
        'enabled',
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
     * @return \App\Keygroup
     */
    public function getSourceObject()
    {
        return Keygroup::find($this->source_id);
    }

    /**
     *Returns the Hostgroup name used as target.
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
     * @return \App\Hostgroup
     */
    public function getTargetObject()
    {
        return Hostgroup::find($this->target_id);
    }

    /**
     * Returns a Collection of Rules with the specified 'source'.
     *
     * @param int $source_id
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findBySource(int $source_id)
    {
        return ControlRule::where('source_id', $source_id)->get();
    }

    /**
     * Returns a Collection of Rules with the specified 'target'.
     *
     * @param int $target_id
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findByTarget(int $target_id)
    {
        return ControlRule::where('target_id', $target_id)->get();
    }

    /**
     * Returns a formatted log line depending of the type.
     *
     * @param  string  $type
     *
     * @return string
     */
    public function getLogLineFor(string $type): string
    {
        switch ($type) {
            case 'CREATE_OR_UPDATE':
                return sprintf("Create or update rule '%s'",
                    $this->name);

            case "DELETE":
                return sprintf("Delete rule '%s'",
                    $this->name);

            default:
                return sprintf("Unknown event on rule '%s'",
                    $this->name);
        }
    }
}
