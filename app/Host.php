<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Host
 *
 * @package App
 *
 * @property string  $hostname
 * @property string  $username
 * @property boolean $enabled
 * @property boolean $synced
 * @property string  $key_hash
 */
class Host extends Model implements Searchable
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hosts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hostname',
        'username',
        'enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hostname' => 'string',
        'username' => 'string',
        'enabled' => 'boolean',
        'synced' => 'boolean',
        'key_hash' => 'string',
    ];

    /**
     * A Host belongs to many Hostgroups (many-to-many)
     *
     * @return BelongsToMany
     */
    public function hostgroups()
    {
        return $this->belongsToMany('App\Hostgroup');
    }

    /**
     * This method return full hostname string, composed by `username@hostname`
     *
     * @return string
     */
    public function getFullHostname()
    {
        return $this->username . '@' . $this->hostname;
    }

    /**
     * Set Host sync status
     *    0 = Host is not sync, it needs to transfer SSH Key file
     *    1 = Host is sync
     *
     * @param bool $synced
     */
    public function setSynced(bool $synced = false)
    {
        $this->synced = $synced;
    }

    /**
     * Scope a query to only include hosts that are not in sync.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotInSync($query)
    {
        return $query->where('synced', '=', false);
    }

    /**
     * Gets all SSH User Keys for Host
     *
     * @param string $bastionSSHPublicKey
     *
     * @return array
     */
    public function getSSHKeysForHost(string $bastionSSHPublicKey = null)
    {
        $sshKeys = array();
        $hostID = $this->id;

        $users = User::whereHas('usergroups.hostgroups.hosts', function (Host $host) use ($hostID) {
            $host->where('hosts.id', $hostID)->where('usergroup_hostgroup_permissions.action', 'allow');
        })->select('username', 'public_key')->where('enabled', 1)->orderBy('username')->get();

        foreach ($users as $user) {
            $content = explode(' ', $user->public_key, 3);
            $content[2] = $user->username . '@ssham';

            $sshKeys[] = join(' ', $content);
        }

        // Add Bastion host SSH public key
        if (!is_null($bastionSSHPublicKey)) {
            $sshKeys[] = $bastionSSHPublicKey;
        }

        return $sshKeys;
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('hosts.show', $this->id);

        return new SearchResult(
            $this,
            $this->hostname,
            $url
        );
    }
}
