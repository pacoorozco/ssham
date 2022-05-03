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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Activitylog\LogOptions;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Host.
 *
 * @property int $id
 * @property string $hostname
 * @property string $username
 * @property int $port
 * @property string $authorized_keys_file
 * @property string $type
 * @property string|null $key_hash
 * @property bool $enabled
 * @property bool $synced
 * @property \App\Enums\HostStatus $status_code
 * @property \Illuminate\Support\Carbon|null $last_rotation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_hostname
 * @property-read \App\Models\Hostgroup[] $groups
 */
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

    protected function username(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    protected function hostname(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    public function fullHostname(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['username'].'@'.$attributes['hostname'],
        );
    }

    public function port(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: setting()->get('ssh_port', 22),
            set: fn ($value) => (int) $value > 0 ? (int) $value : null,
        );
    }

    public function hasCustomPort(): bool
    {
        return ! is_null($this->attributes['port']);
    }

    public function hasCustomAuthorizedKeysFile(): bool
    {
        return ! is_null($this->attributes['authorized_keys_file']);
    }

    public function authorizedKeysFile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: setting()->get('authorized_keys', ''),
            set: fn ($value) => ! empty($value) ? $value : null,
        );
    }

    public function scopeNotInSync(Builder $query): Builder
    {
        return $query->where('synced', false);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }

    public string $searchableType = 'Hosts';

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
        $this->last_rotation = now();
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
        if (! is_null($bastionSSHPublicKey)) {
            $sshKeys[] = $bastionSSHPublicKey;
        }

        return $sshKeys;
    }

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['hostname', 'username']);
    }
}
