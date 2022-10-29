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

namespace Tests\Unit\Models;

use App\Models\Keygroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\Unit\ModelTestCase;

class KeygroupTest extends ModelTestCase
{
    /** @test */
    public function it_contains_valid_fillable_properties(): void
    {
        $m = new Keygroup();
        $this->assertEquals([
            'name',
            'description',
        ], $m->getFillable());
    }

    /** @test */
    public function it_has_keys_relation(): void
    {
        $m = new Keygroup();
        $r = $m->keys();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    /** @test */
    public function it_has_rules_relation(): void
    {
        $m = new Keygroup();
        $r = $m->rules();
        $this->assertInstanceOf(HasMany::class, $r);
    }
}
