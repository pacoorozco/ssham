<?php

namespace Tests\Unit;

use App\Host;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\ModelTestCase;

class HostTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
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

    public function test_contains_valid_casts_properties()
    {
        $m = new Host();
        $this->assertEquals([
            'id' => 'int',
            'hostname' => 'string',
            'username' => 'string',
            'port' => 'int',
            'authorized_keys_file' => 'string',
            'enabled' => 'boolean',
            'status_code' => 'string',
            'synced' => 'boolean',
            'key_hash' => 'string',
            'last_rotation' => 'datetime',
        ], $m->getCasts());
    }

    public function test_hostgroups_relation()
    {
        $m = new Host();
        $r = $m->groups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    public function test_username_is_lowercase()
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

    public function test_hostname_is_lowercase()
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

    public function test_full_hostname_attribute()
    {
        $m = new Host();
        $input = [
            'username' => 'root',
            'hostname' => 'server1.domain.local',
            'port' => '12345',
        ];
        $want = 'root@server1.domain.local:12345';

        $m->username = $input['username'];
        $m->hostname = $input['hostname'];
        $m->port = $input['port'];

        $this->assertEquals($want, $m->getAttribute('full_hostname'));
    }

    public function test_setSynced()
    {
        $m = new Host();

        $test_data = [
            [
                'given' => false,
                'input' => true,
                'want' => true,
            ],
            [
                'given' => false,
                'input' => false,
                'want' => false,
            ],
            [
                'given' => true,
                'input' => false,
                'want' => false,
            ],
            [
                'given' => true,
                'input' => true,
                'want' => true,
            ],
        ];

        foreach ($test_data as $case) {
            $m->synced = $case['given'];
            $m->setSynced($case['input'], true);
            $this->assertEquals($case['want'], $m->synced);
        }
    }
}
