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
use App\Presenters\HostPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Host.
 *
 * @property-read int $id
 * @property string $hostname
 * @property string $username
 * @property int|null $port - null value means using the default setting.
 * @property string|null $authorized_keys_file - null value means using the default setting.
 * @property string $type
 * @property string|null $key_hash
 * @property bool $enabled
 * @property bool $synced
 * @property HostStatus $status_code
 * @property Carbon|null $last_rotation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $full_hostname
 * @property-read Hostgroup[] $groups
 */
class Host extends Model implements Searchable
{
    use HasFactory;
    use LogsActivity;
    use Presentable;

    public string $searchableType = 'Hosts';

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

    /** @return Attribute<string, never> */
    public function fullHostname(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->username.'@'.$this->hostname,
        );
    }

    /** @return Attribute<never, int> */
    public function port(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => (int) $value > 0 ? (int) $value : null,
        );
    }

    public function portOrDefaultSetting(): int
    {
        return $this->port ?? $this->portDefaultSetting();
    }

    public function portDefaultSetting(): int
    {
        return setting()->get('ssh_port', 0);
    }

    /** @return Attribute<never, string> */
    public function authorizedKeysFile(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ! empty($value) ? $value : null,
        );
    }

    public function authorizedKeysFileOrDefaultSetting(): string
    {
        return $this->authorized_keys_file ?? $this->authorizedKeysFileDefaultSetting();
    }

    public function authorizedKeysFileDefaultSetting(): string
    {
        return setting()->get('authorized_keys', '');
    }

    public function hasCustomPort(): bool
    {
        return ! is_null($this->attributes['port']);
    }

    public function hasCustomAuthorizedKeysFile(): bool
    {
        return ! is_null($this->attributes['authorized_keys_file']);
    }

    /**
     * @param Builder<Host> $query
     * @return Builder<Host>
     */
    public function scopeWithPendingChanges(Builder $query): Builder
    {
        return $query->where('synced', false);
    }

    /**
     * @param Builder<Host> $query
     * @return Builder<Host>
     */
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
        $this->last_rotation = now();
        $this->synced = ($status === HostStatus::SUCCESS_STATUS);
        $this->save();
    }

    /** @return Collection<int, string> */
    public function getSSHKeysForHost(): Collection
    {
        $sshKeys = collect();

        // Get the host group IDs for this host.
        $hostGroupIds = $this->groups()->pluck('hostgroups.id');

        // Get the key group IDs for the rules that allow access to the host.
        $keyGroupIds = ControlRule::whereIn('target_id', $hostGroupIds)
            ->select('source_id')
            ->distinct()
            ->pluck('source_id');

        // Get all keys for the key groups that allow access to the host and are enabled.
        return Keygroup::whereIn('id', $keyGroupIds)
            ->with('keys')
            ->get()
            ->pluck('keys')
            ->flatten()
            ->where('enabled', true)
            ->map(fn ($key) => $key->public);
    }

    /** @return BelongsToMany<Hostgroup> */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Hostgroup::class);
    }

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Host ':subject.full_hostname' was $eventName");
    }

    // TODO: Needs a refactor to make code more readable.

    /** @return Attribute<never, string> */
    protected function username(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    /** @return Attribute<never, string> */
    protected function hostname(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }
}
