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

use App\Enums\HostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Host extends Model implements Searchable
{
    use HasFactory;

    protected $table = 'hosts';

    protected $fillable = [
        'hostname',
        'username',
        'port',
        'authorized_keys_file',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'synced' => 'boolean',
        'last_rotation' => 'datetime',
    ];

    protected $attributes = [
        'status_code' => HostStatus::INITIAL_STATUS,
    ];

    /**
     * A Host belongs to many Hostgroups (many-to-many).
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Hostgroup::class);
    }

    public function setUsernameAttribute(string $value): void
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function setHostnameAttribute(string $value): void
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    public function getFullHostnameAttribute(): string
    {
        return "{$this->username}@{$this->hostname}:{$this->port}";
    }

    public function getPortAttribute(string $value): int
    {
        $defaultPort = setting()->get('ssh_port');

        return $value ?? (int) $defaultPort;
    }

    public function scopeNotInSync(Builder $query): Builder
    {
        return $query->where('synced', '=', false);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', '=', true);
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->hostname,
            route('hosts.show', $this->id)
        );
    }

    public function setStatus(string $status): void
    {
        // TODO: validate status

        $this->status_code = $status;
        $this->last_rotation = now()->timestamp;
        $this->synced = (HostStatus::SUCCESS_STATUS === $status);
        $this->save();
    }

    // TODO: Needs a refactor to make code more readable.
    public function getSSHKeysForHost(?string $bastionSSHPublicKey = null): array
    {
        $sshKeys = [];
        $hostGroups = $this->groups;
        foreach ($hostGroups as $hostGroup) {
            $rules = $hostGroup->getRelatedRules();
            foreach ($rules as $rule) {
                $keygroup = $rule->getSourceObject();
                $keys = $keygroup->keys()->where('enabled', true)->get();
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
        if (! is_null($bastionSSHPublicKey)) {
            $sshKeys[] = $bastionSSHPublicKey;
        }

        return $sshKeys;
    }
}
