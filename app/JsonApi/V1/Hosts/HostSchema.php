<?php

namespace App\JsonApi\V1\Hosts;

use App\Models\Host;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class HostSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Host::class;

    /**
     * Get the resource fields.
     * @return array<mixed>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('fullHostname')->readOnly(),
            Str::make('hostname')->readOnlyOnUpdate(),
            Str::make('username')->readOnlyOnUpdate(),
            Number::make('port'),
            Str::make('authorizedKeysFile'),
            Boolean::make('enabled'),
            Boolean::make('synced')->readOnly(),
            Str::make('syncedStatus', 'status_code')
                ->serializeUsing(
                    static fn ($value) => $value->value
                )
                ->readOnly(),
            DateTime::make('syncedAt', 'last_rotation')->readOnly(),
            DateTime::make('createdAt')->readOnly(),
            BelongsToMany::make('groups')->type('hostgroups'),
        ];
    }

    /**
     * Get the resource filters.
     * @return array<mixed>
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
