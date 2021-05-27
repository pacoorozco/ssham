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
use App\Enums\HostStatus;
use App\Presenters\HostPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Host extends Model implements Searchable
{
    use HasFactory;
    use Presentable;

    protected string $presenter = HostPresenter::class;

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
        'status_code' => HostStatus::class,
        'port' => 'int',
    ];

    protected $attributes = [
        'status_code' => HostStatus::INITIAL_STATUS,
    ];

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
        return "{$this->username}@{$this->hostname}";
    }

    public function getPortOrDefault(): int
    {
        return $this->hasCustomPort() ? $this->port : setting()->get('ssh_port');
    }

    public function hasCustomPort(): bool
    {
        return $this->port !== 0;
    }

    public function getAuthorizedKeysFileOrDefault(): string
    {
        return $this->hasCustomAuthorizedKeysFile() ? $this->authorized_keys_file : setting()->get('authorized_keys');
    }

    public function hasCustomAuthorizedKeysFile(): bool
    {
        return ($this->authorized_keys_file !== '');
    }

    public function scopeNotInSync(Builder $query): Builder
    {
        return $query->where('synced', false);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->hostname,
            route('hosts.show', $this->id)
        );
    }

    public function setStatus(HostStatus $status): void
    {
        $this->status_code = $status;
        $this->last_rotation = now()->timestamp;
        $this->synced = ($status->is(HostStatus::SUCCESS_STATUS));
        $this->save();
    }

    // TODO: Needs a refactor to make code more readable.
    public function getSSHKeysForHost(?string $bastionSSHPublicKey = null): array
    {
        $sshKeys = [];
        $hostGroups = $this->groups;
        foreach ($hostGroups as $hostGroup) {
            $rules = $hostGroup->rules;
            foreach ($rules as $rule) {
                $keygroup = $rule->source;
                $keys = $keygroup->keys()->where('enabled', true)->get();
                foreach ($keys as $key) {
                    switch ($rule->action) {
                        case ControlRuleAction::Deny:
                            unset($sshKeys[$key->username]);
                            break;
                        case ControlRuleAction::Allow:
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
        if (!is_null($bastionSSHPublicKey)) {
            $sshKeys[] = $bastionSSHPublicKey;
        }

        return $sshKeys;
    }
}
