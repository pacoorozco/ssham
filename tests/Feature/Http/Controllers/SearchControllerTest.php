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

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Generator;
use Tests\Feature\TestCase;

final class SearchControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function users_should_see_the_search_view(): void
    {
        Key::factory()->create([
            'name' => 'qwerty_key',
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('search'))
            ->assertSuccessful()
            ->assertViewIs('search.index');
    }

    #[Test]
    public function users_should_see_the_search_view_when_using_an_empty_query(): void
    {
        Key::factory()->create([
            'name' => 'qwerty_key',
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('search', ['q' => '']))
            ->assertSuccessful()
            ->assertViewIs('search.index');
    }

    #[Test]
    #[DataProvider('provideSearchQueriesAndExpectedResults')]
    public function users_should_see_the_search_results(
        string $query,
        array $want,
    ): void {
        Key::factory()->create([
            'name' => 'qwerty_key',
        ]);
        Host::factory()->create([
            'hostname' => 'qwerty_host',
        ]);
        Keygroup::factory()->create([
            'name' => 'qwerty_keys_group',
        ]);
        Hostgroup::factory()->create([
            'name' => 'qwerty_hosts_group',
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('search', ['q' => $query]))
            ->assertSuccessful()
            ->assertViewIs('search.results')
            ->assertSee($want);
    }

    public static function provideSearchQueriesAndExpectedResults(): Generator
    {
        yield 'find a key, host, keys group and hosts group' => [
            'query' => 'qwerty',
            'want' => ['qwerty_key', 'qwerty_host', 'qwerty_keys_group', 'qwerty_hosts_group'],
        ];

        yield 'find a key and keys group' => [
            'query' => 'key',
            'want' => ['qwerty_key', 'qwerty_keys_group'],
        ];

        yield 'find a host and hosts group' => [
            'query' => 'host',
            'want' => ['qwerty_host', 'qwerty_hosts_group'],
        ];

        yield 'find a keys group and hosts group' => [
            'query' => 'group',
            'want' => ['qwerty_keys_group', 'qwerty_hosts_group'],
        ];

        yield 'find a keys group' => [
            'query' => 'keys_group',
            'want' => ['qwerty_keys_group'],
        ];

        yield 'no results found' => [
            'query' => 'foo',
            'want' => ['No matching records found'],
        ];
    }
}
