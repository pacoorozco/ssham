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

use App\Presenters\KeyPresenter;
use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Laracodes\Presenter\Traits\Presentable;
use PacoOrozco\OpenSSH\PublicKey;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Key.
 *
 * @property string $id
 * @property string $name
 * @property bool $enabled
 * @property string $type
 * @property string $public
 * @property string $private
 * @property string $fingerprint
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Keygroup[] $groups
 */
class Key extends Model implements Searchable
{
    use HasFactory;
    use LogsActivity;
    use Presentable;
    use UsesUUID;

    public string $searchableType = 'SSH Keys';

    protected string $presenter = KeyPresenter::class;

    protected $table = 'keys';

    protected $fillable = [
        'name',
        'public',
        'private',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * A Key belongs to many Key groups (many-to-many).
     *
     * @return BelongsToMany<Keygroup>
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Keygroup::class);
    }

    private function publicKeyComment(): string
    {
        return 'SSHAM['.$this->name.']';
    }

    /** @return Attribute<string, string> */
    protected function public(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::of($value)
                ->words(2, ' ')
                ->append($this->publicKeyComment())
                ->toString(),
            set: fn (string $value) => [
                'public' => $value,
                'fingerprint' => PublicKey::fromString($value)->getFingerPrint('md5'),
            ],
        );
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('keys.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    public function hasPrivateKey(): bool
    {
        return ! empty($this->private);
    }

    /** @codeCoverageIgnore */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'enabled'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Key ':subject.name' was {$eventName}");
    }

    /** @codeCoverageIgnore */
    public function tapActivity(Activity $activity, string $eventName): void
    {
        // Do not use store the subject_id because we use UUID which are not compatible.
        $activity->subject_id = null;
    }
}
