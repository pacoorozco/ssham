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

namespace Tests\Feature\Actions;

use App\Actions\CreateHostGroupAction;
use App\Actions\UpdateHostGroupAction;
use App\Models\Host;
use App\Models\Hostgroup;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateHostGroupActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider providesMembershipData
     */
    public function it_updates_a_host_group(
        array $members,
    ): void {
        // Create some hosts to test the membership.
        Host::factory()
            ->count(3)
            ->create();

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        $action = app(UpdateHostGroupAction::class);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

       $action(
            group: $group,
            name: $want->name,
            description: $want->description,
            members: $members,
        );

        $this->assertDatabaseHas(Hostgroup::class, [
            'id' => $group->id,
            'name' => $want->name,
            'description' => $want->description,
        ]);

        $group->refresh();

        $this->assertCount(count($members), $group->hosts);
    }

    public function providesMembershipData(): Generator
    {
        yield '! members' => [
            'members' => [],
        ];

        yield 'one member' => [
            'members' => [1],
        ];

        yield 'all members' => [
            'members' => [1, 2, 3],
        ];
    }
}
