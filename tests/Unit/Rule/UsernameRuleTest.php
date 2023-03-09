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

namespace Tests\Unit\Rule;

use App\Rules\UsernameRule;
use Generator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsernameRuleTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function usernames_created_by_faker_should_pass(): void
    {
        $rule = new UsernameRule();

        for ($i = 0; $i <= 10; $i++) {
            $username = $this->faker->userName;

            $this->assertTrue($rule->passes('username', $username), "Testcase: $username");
        }
    }

    /**
     * @test
     * @dataProvider provideInvalidUsernames
     */
    public function usernames_containing_invalid_characters_should_fail(
        string $input,
    ): void {
        $rule = new UsernameRule();

        $this->assertFalse($rule->passes('username', $input));
    }

    public static function provideInvalidUsernames(): Generator
    {
        yield 'username beginning with hyphen' => [
            'input' => '-root',
        ];

        yield 'username beginning with underscore' => [
            'input' => '_root',
        ];

        yield 'username beginning with a dot' => [
            'input' => '.root',
        ];

        yield 'username containing invalid characters' => [
            'input' => 'root\!@#·$%&/()=?¿¡^*[]{};,:',
        ];

        yield 'username containing spaces' => [
            'input' => 'r o o t',
        ];
    }
}
