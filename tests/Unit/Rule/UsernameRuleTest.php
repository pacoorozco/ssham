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
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Unit\Rule;

use App\Rules\UsernameRule;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsernameRuleTest extends TestCase
{
    use WithFaker;

    protected UsernameRule $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new UsernameRule();
    }

    /** @test */
    public function usernames_created_by_faker_should_pass(): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $username = $this->faker->userName;
            $this->assertTrue($this->rule->passes('username', $username), "Testcase: {$username}");
        }
    }

    /** @test */
    public function usernames_containing_invalid_characters_should_fail(): void
    {
        $testCases = [
            'Username beginning with hyphen' => '-root',
            'Username beginning with underscore' => '_root',
            'Username beginning with a dot' => '.root',
            'Username containing invalid characters' => '\!@#·$%&/()=?¿¡^*[]{};,:',
            'Username containing spaces' => 'r o o t',
        ];

        foreach ($testCases as $testCase => $testData) {
            $this->assertFalse($this->rule->passes('username', $testData), "Testcase: {$testCase}");
        }
    }
}
