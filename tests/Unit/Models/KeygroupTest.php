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
use Tests\ModelTestCase;

class KeygroupTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
    {
        $m = new Keygroup();
        $this->assertEquals([
            'name',
            'description',
        ], $m->getFillable());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new Keygroup();
        $this->assertEquals([
            'id' => 'int',
            'name' => 'string',
            'description' => 'string',
        ], $m->getCasts());
    }

    public function test_users_relation()
    {
        $m = new Keygroup();
        $r = $m->keys();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }
}