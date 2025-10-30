<?php

namespace App\JsonApi\V1\Hostgroups;

use App\Models\Hostgroup;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class HostgroupSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Hostgroup::class;

    /**
     * Get the resource fields.
     *
     * @return array<mixed>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('name'),
            Str::make('description'),
            BelongsToMany::make('hosts'),
            DateTime::make('createdAt')->sortable()->readOnly(),
        ];
    }

    /**
     * Get the resource filters.
     *
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
