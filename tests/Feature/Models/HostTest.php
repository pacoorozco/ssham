<?php

namespace Tests\Feature\Models;

use App\Host;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function test_scopeEnabled_returns_correct_data()
    {
        // two disabled Host
        factory(Host::class, 2)->create([
            'enabled' => false,
        ]);

        // three enabled Host
        factory(Host::class, 3)->create([
            'enabled' => true,
        ]);

        $got = Host::enabled()->get();

        $this->assertCount(3, $got);
    }

    public function test_scopeEnabled_returns_no_data_if_all_hosts_are_disabled()
    {
        // two disabled Host
        factory(Host::class, 2)->create([
            'enabled' => false,
        ]);

        $got = Host::enabled()->get();

        $this->assertEmpty($got);
    }

    public function test_scopeNotInSync_returns_correct_data()
    {
        // two synced Host
        factory(Host::class, 2)->create([
            'synced' => true,
        ]);

        // three not in sync Host
        factory(Host::class, 3)->create([
            'synced' => false,
        ]);

        $got = Host::notInSync()->get();

        $this->assertCount(3, $got);
    }

    public function test_scopeNotInSync_returns_no_data_if_all_hosts_are_disabled()
    {
        // two synced Host
        factory(Host::class, 2)->create([
            'synced' => true,
        ]);

        $got = Host::notInSync()->get();

        $this->assertEmpty($got);
    }
}
