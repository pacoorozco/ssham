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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

abstract class ModelTestCase extends TestCase
{
    /**
     * @param  array  $fillable
     * @param  array  $guarded
     * @param  array  $hidden
     * @param  array  $visible
     * @param  array  $casts
     * @param  array  $dates
     * @param  string  $collectionClass
     * @param  null  $table
     * @param  string  $primaryKey
     * @param  null  $connection
     *
     * - `$fillable` -> `getFillable()`
     * - `$guarded` -> `getGuarded()`
     * - `$table` -> `getTable()`
     * - `$primaryKey` -> `getKeyName()`
     * - `$connection` -> `getConnectionName()`: in case multiple connections exist.
     * - `$hidden` -> `getHidden()`
     * - `$visible` -> `getVisible()`
     * - `$casts` -> `getCasts()`: note that method appends incrementing key.
     * - `$dates` -> `getDates()`: note that method appends `[static::CREATED_AT, static::UPDATED_AT]`.
     * - `newCollection()`: assert collection is exact type. Use `assertEquals` on `get_class()` result, but not
     * `assertInstanceOf`.
     */
    protected function runConfigurationAssertions(
        Model $model,
        $fillable = [],
        $hidden = [],
        $guarded = ['*'],
        $visible = [],
        $casts = ['id' => 'int'],
        $dates = ['created_at', 'updated_at'],
        $collectionClass = Collection::class,
        $table = null,
        $primaryKey = 'id',
        $connection = null
    ) {
        $this->assertEquals($fillable, $model->getFillable());
        $this->assertEquals($guarded, $model->getGuarded());
        $this->assertEquals($hidden, $model->getHidden());
        $this->assertEquals($visible, $model->getVisible());
        $this->assertEquals($casts, $model->getCasts());
        $this->assertEquals($dates, $model->getDates());
        $this->assertEquals($primaryKey, $model->getKeyName());

        $c = $model->newCollection();
        $this->assertEquals($collectionClass, get_class($c));
        $this->assertInstanceOf(Collection::class, $c);

        if ($connection !== null) {
            $this->assertEquals($connection, $model->getConnectionName());
        }

        if ($table !== null) {
            $this->assertEquals($table, $model->getTable());
        }
    }

    /**
     * @param  HasMany  $relation
     * @param  string  $key
     * @param  string  $parent
     * @param  \Closure  $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getQualifiedParentKeyName()`: in case of `HasOneOrMany` relation, there is no `getLocalKey()` method, so this
     * one should be asserted.
     */
    protected function assertHasManyRelation($relation, Model $model, Model $related, $key = null, $parent = null, \Closure $queryCheck = null)
    {
        $this->assertInstanceOf(HasMany::class, $relation);

        if (! is_null($queryCheck)) {
            $queryCheck->bindTo($this);
            $queryCheck($relation->getQuery(), $model, $relation);
        }

        if (is_null($key)) {
            $key = $model->getForeignKey();
        }

        $this->assertEquals($key, $relation->getForeignKeyName());

        if (is_null($parent)) {
            $parent = $model->getKeyName();
        }

        $this->assertEquals($model->getTable().'.'.$parent, $relation->getQualifiedParentKeyName());
    }

    /**
     * @param  BelongsTo  $relation
     * @param  string  $key
     * @param  string  $owner
     * @param  \Closure  $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getOwnerKey()`: `BelongsTo` relation and its extendings.
     */
    protected function assertBelongsToRelation($relation, Model $model, Model $related, $key, $owner = null, \Closure $queryCheck = null)
    {
        $this->assertInstanceOf(BelongsTo::class, $relation);

        if (! is_null($queryCheck)) {
            $queryCheck->bindTo($this);
            $queryCheck($relation->getQuery(), $model, $relation);
        }

        $this->assertEquals($key, $relation->getForeignKey());

        if (is_null($owner)) {
            $owner = $related->getKeyName();
        }

        $this->assertEquals($owner, $relation->getOwnerKey());
    }
}
