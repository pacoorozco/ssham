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
use App\Actions\CreateHostGroupAction;
use App\Models\Host;
use App\Models\Hostgroup;
use Generator;
use Tests\Feature\TestCase;

final class CreateHostGroupActionTest extends TestCase
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
    public function it_creates_a_host_group(
        int $members_count,
    ): void {
        $action = app(CreateHostGroupAction::class);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $members = Host::query()
            ->take($members_count)
            ->pluck('id')
            ->toArray();

        $group = $action(
            name: $want->name,
            description: $want->description,
            members: $members,
        );

        $this->assertDatabaseHas(Hostgroup::class, [
            'name' => $want->name,
            'description' => $want->description,
        ]);

        $this->assertCount($members_count, $group->hosts);
    }

    public static function providesMembershipData(): Generator
    {
        yield 'empty members' => [
            'members_count' => 0,
        ];

        yield 'one member' => [
            'members_count' => 1,
        ];

        yield 'more than one members' => [
            'members_count' => 3,
        ];
    }
}
