<?php

namespace Tests\Unit\Models;

use App\Models\Host;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\ModelTestCase;

class HostTest extends ModelTestCase
{
    /** @test */
    public function contains_valid_fillable_properties(): void
    {
        $m = new Host();
        $this->assertEquals([
            'hostname',
            'username',
            'port',
            'authorized_keys_file',
            'enabled',
        ], $m->getFillable());
    }

    /** @test */
    public function contains_valid_casts_properties(): void
    {
        $m = new Host();
        $this->assertEquals([
            'id' => 'int',
            'enabled' => 'boolean',
            'synced' => 'boolean',
            'last_rotation' => 'datetime',
        ], $m->getCasts());
    }

    /** @test */
    public function has_groups_relation(): void
    {
        $m = new Host();
        $r = $m->groups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    /** @test */
    public function username_is_lowercase(): void
    {
        $testCases = [
            'User' => 'user',
            'ADMIN' => 'admin',
            'user' => 'user',
            'admin' => 'admin',
        ];

        foreach ($testCases as $input => $want) {
            /** @var Host $host */
            $host = Host::factory()->makeOne([
                'username' => $input,
            ]);
            $this->assertEquals($want, $host->username);
        }
    }

    /** @test */
    public function hostname_is_lowercase(): void
    {
        $testCases = [
            'server.domain.local' => 'server.domain.local',
            'Server.Domain.Local' => 'server.domain.local',
            'SERVER' => 'server',
            'SERVER.domain.LOCAL' => 'server.domain.local',
        ];

        foreach ($testCases as $input => $want) {
            /** @var Host $host */
            $host = Host::factory()->makeOne([
                'hostname' => $input,
            ]);
            $this->assertEquals($want, $host->hostname);
        }
    }
}
