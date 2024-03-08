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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use App\Actions\UpdateHostGroupAction;
use App\Models\Host;
use App\Models\Hostgroup;
use Generator;
use Tests\Feature\TestCase;

class UpdateHostGroupActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Host::factory()
            ->count(3)
            ->create();
    }

    #[Test]
    #[DataProvider('providesMembershipData')]
    public function it_updates_a_host_group(
        int $members_count,
    ): void {
        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        $action = app(UpdateHostGroupAction::class);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $members = Host::query()
            ->take($members_count)
            ->pluck('id')
            ->toArray();

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

        $this->assertCount($members_count, $group->hosts);
    }

    public static function providesMembershipData(): Generator
    {
        yield 'empty members' => [
            'members' => 0,
        ];

        yield 'one member' => [
            'members' => 1,
        ];

        yield 'more than one members' => [
            'members' => 3,
        ];
    }
}
