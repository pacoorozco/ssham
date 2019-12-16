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

class Host extends Model
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
        'type'
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
     * @param int $synced
     */
    public function setSynced($synced = 0)
    {
        $this->synced = $synced;
    }

    /**
     * Set Host Keys Files Hash. It keeps a hash of last transferred SSH Key File
     *
     * @param $keyHash
     */
    public function setKeyHash($keyHash)
    {
        $this->key_hash = $keyHash;
    }

    /**
     * Set Host enabled status
     *     1, enabled, true = Host is enabled
     *     0, disabled, false = Host is disabled
     *
     * @param $status
     */
    public function setStatus($status)
    {
        $status = ($status === 1 || $status == 'enabled' || $status === true) ? 1 : 0;
        $this->enabled = $status;
    }

    /**
     * Gets all SSH User Keys for Host
     *
     * @return array
     */
    public function getSSHKeysForHost()
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

        return $sshKeys;
    }
}
