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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Host.
 *
 *
 * @property int $id
 * @property string $hostname
 * @property string $username
 * @property string $full_hostname
 * @property int $port
 * @property string $authorized_keys_file
 * @property bool $enabled
 * @property bool $synced
 * @property string $status_code
 * @property string $key_hash
 */
class Host extends Model implements Searchable
{
    /**
     * Host statuses.
     */
    const INITIAL_STATUS = 'INITIAL';
    const AUTH_FAIL_STATUS = 'AUTHFAIL';
    const PUBLIC_KEY_FAIL_STATUS = 'KEYAUTHFAIL';
    const GENERIC_FAIL_STATUS = 'GENERICFAIL';
    const SUCCESS_STATUS = 'SUCCESS';
    const HOST_FAIL_STATUS = 'HOSTFAIL';

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
        'port',
        'authorized_keys_file',
        'enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int',
        'hostname' => 'string',
        'username' => 'string',
        'port' => 'int',
        'authorized_keys_file' => 'string',
        'enabled' => 'boolean',
        'status_code' => 'string',
        'synced' => 'boolean',
        'key_hash' => 'string',
        'last_rotation' => 'datetime',
    ];

    /**
     * A Host belongs to many Hostgroups (many-to-many).
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Hostgroup');
    }

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'synced' => false,
        'status_code' => self::INITIAL_STATUS,
        'enabled' => true,
    ];

    /**
     * Set the username Host attribute to lowercase.
     *
     * @param  string  $value
     */
    public function setUsernameAttribute(string $value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    /**
     * Set the hostname Host attribute to lowercase.
     *
     * @param  string  $value
     */
    public function setHostnameAttribute(string $value)
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    /**
     * This method return full hostname string, composed by `username@hostname:port`.
     *
     * @return string
     */
    public function getFullHostnameAttribute()
    {
        return $this->username.'@'.$this->hostname.':'.$this->port;
    }

    /**
     * Set Host sync status
     *    0 = Host is not sync, it needs to transfer SSH Key file
     *    1 = Host is sync.
     *
     * @param  bool  $synced
     * @param  bool  $skip_save  - if true, skip saving the model (for testing)
     */
    public function setSynced(bool $synced = false, bool $skip_save = false)
    {
        $this->synced = $synced;

        if (! $skip_save) {
            $this->save();
        }
    }

    /**
     * Scope a query to only include hosts that are not in sync.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotInSync($query)
    {
        return $query->where('synced', '=', false);
    }

    /**
     * Scope a query to only include hosts that are enabled.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', '=', true);
    }

    /**
     * Gets all SSH User Keys for Host.
     *
     * @param  string  $bastionSSHPublicKey
     *
     * @return array
     */
    public function getSSHKeysForHost(string $bastionSSHPublicKey = null)
    {
        $sshKeys = [];
        $hostGroups = $this->groups;
        foreach ($hostGroups as $hostGroup) {
            $rules = $hostGroup->getRelatedRules();
            foreach ($rules as $rule) {
                $keygroup = $rule->getSourceObject();
                $keys = $keygroup->keys;
                foreach ($keys as $key) {
                    switch ($rule->action) {
                        case 'deny':
                            unset($sshKeys[$key->username]);
                            break;
                        case 'allow':
                            $content = explode(' ', $key->public, 3);
                            $content[2] = $key->username.'@ssham';
                            $sshKeys[$key->username] = join(' ', $content);
                            break;
                        default:
                            // There is no more cases, but just in case (NOOP).
                    }
                }
            }
        }

        return $sshKeys;
    }

    /**
     * Used to implement search in this model.
     *
     * Define which fields could be searched and which URL will be returned.
     *
     * @return \Spatie\Searchable\SearchResult
     */
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
