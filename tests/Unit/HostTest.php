<?php

namespace Tests\Unit;

use App\Models\Host;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\ModelTestCase;

class HostTest extends ModelTestCase
{
    /** @test */
    public function contains_valid_fillable_properties()
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
    public function contains_valid_casts_properties()
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
    public function has_groups_relation()
    {
        $m = new Host();
        $r = $m->groups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    /** @test */
    public function username_is_lowercase()
    {
        $m = new Host();

        $test_data = [
            'User' => 'user',
            'ADMIN' => 'admin',
            'user' => 'user',
            'admin' => 'admin',
        ];

        foreach ($test_data as $input => $want) {
            $m->username = $input;
            $this->assertEquals($want, $m->getAttribute('username'));
        }
    }

    /** @test */
    public function hostname_is_lowercase()
    {
        $m = new Host();

        $test_data = [
            'server.domain.local' => 'server.domain.local',
            'Server.Domain.Local' => 'server.domain.local',
            'SERVER' => 'server',
            'SERVER.domain.LOCAL' => 'server.domain.local',
        ];

        foreach ($test_data as $input => $want) {
            $m->hostname = $input;
            $this->assertEquals($want, $m->getAttribute('hostname'));
        }
    }
}
