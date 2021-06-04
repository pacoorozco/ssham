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

namespace Tests\Feature\Http\Controllers;

use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->create();
    }

    /** @test */
    public function index_method_should_return_proper_view(): void
    {
        Key::factory()->create([
            'username' => 'qwerty_key',
        ]);
        Host::factory()->create([
            'hostname' => 'qwerty_host',
        ]);
        Keygroup::factory()->create([
            'name' => 'qwerty_keygroup',
        ]);
        Hostgroup::factory()->create([
            'name' => 'qwerty_hostgroup',
        ]);

        $testCases = [
            'qwerty' => ['qwerty_key', 'qwerty_host', 'qwerty_keygroup', 'qwerty_hostgroup'],
            'key' => ['qwerty_key', 'qwerty_keygroup'],
            'host' => ['qwerty_host', 'qwerty_hostgroup'],
            'group' => ['qwerty_keygroup', 'qwerty_hostgroup'],
            'keygroup' => ['qwerty_keygroup'],
            'hostgroup' => ['qwerty_hostgroup'],
        ];

        foreach ($testCases as $searchString => $expectedResults) {
            $response = $this
                ->actingAs($this->user)
                ->get(route('search', ['q' => $searchString]));

            $response->assertSuccessful();
            $response->assertViewIs('search.results');
            $this->assertResultsArePresent($response, $expectedResults);
        }
    }

    private function assertResultsArePresent(TestResponse $response, array $expectedResults): void
    {
        foreach ($expectedResults as $expectedResult) {
            $response->assertSee($expectedResult);
        }
    }
}
