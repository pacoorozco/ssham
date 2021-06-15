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

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Gate;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

abstract class ApiTestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesJsonApiRequests;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function disableAuthorization(): void
    {
        Gate::before(function () {
            return true;
        });
    }
}
