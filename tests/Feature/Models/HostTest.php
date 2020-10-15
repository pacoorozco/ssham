<?php

namespace Tests\Feature\Models;

use App\ControlRule;
use App\Host;
use App\Hostgroup;
use App\Key;
use App\Keygroup;
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

    /** @test */
    public function it_returns_the_expected_number_of_keys_for_a_given_host()
    {
        $host = factory(Host::class)->create();

        $hostGroup = factory(Hostgroup::class)->create();
        $hostGroup->hosts()->save($host);

        // These two keys should appear on the getSSHKeysForHost() list.
        $key1 = factory(Key::class)->create(['username' => 'key1']);
        $key2 = factory(Key::class)->create(['username' => 'key2']);
        $allowedKeyGroup = factory(Keygroup::class)->create();
        $allowedKeyGroup->keys()->saveMany([
            $key1,
            $key2,
        ]);

        factory(ControlRule::class)->create([
            'source_id' => $allowedKeyGroup->id,
            'target_id' => $hostGroup->id,
            'action' => 'allow',
        ]);

        // This key should NOT appear on the getSSHKeysForHost() list.
        $key3 = factory(Key::class)->create();
        $deniedKeyGroup = factory(Keygroup::class)->create();
        $deniedKeyGroup->keys()->save(
            $key3
        );

        factory(ControlRule::class)->create([
            'source_id' => $deniedKeyGroup->id,
            'target_id' => $hostGroup->id,
            'action' => 'deny',
        ]);

        $got = $host->getSSHKeysForHost();
        $this->assertCount(2, $got);
        $this->assertArrayHasKey($key1->username, $got);
        $this->assertArrayHasKey($key2->username, $got);
        $this->assertArrayNotHasKey($key3->username, $got);
    }

    /** @test */
    public function it_returns_empty_array_when_key_is_allowed_and_denied_for_a_given_host()
    {
        $host = factory(Host::class)->create();

        $hostGroup = factory(Hostgroup::class)->create();
        $hostGroup->hosts()->save($host);

        $key1 = factory(Key::class)->create(['username' => 'key1']);
        $allowedKeyGroup = factory(Keygroup::class)->create();
        $allowedKeyGroup->keys()->save($key1);

        factory(ControlRule::class)->create([
            'source_id' => $allowedKeyGroup->id,
            'target_id' => $hostGroup->id,
            'action' => 'allow',
        ]);

        $deniedKeyGroup = factory(Keygroup::class)->create();
        $deniedKeyGroup->keys()->save($key1);

        factory(ControlRule::class)->create([
            'source_id' => $deniedKeyGroup->id,
            'target_id' => $hostGroup->id,
            'action' => 'deny',
        ]);

        $got = $host->getSSHKeysForHost();
        $this->assertCount(0, $got);
    }

    /** @test */
    public function it_returns_the_expected_number_of_keys_when_key_is_disabled()
    {
        $host = factory(Host::class)->create();

        $hostGroup = factory(Hostgroup::class)->create();
        $hostGroup->hosts()->save($host);

        // This key should appear on the getSSHKeysForHost() list.
        $key1 = factory(Key::class)->create(['username' => 'key1']);
        // This key should NOT appear, because is disabled, on the getSSHKeysForHost() list.
        $key2 = factory(Key::class)->create(['username' => 'key2', 'enabled' => false]);
        $allowedKeyGroup = factory(Keygroup::class)->create();
        $allowedKeyGroup->keys()->saveMany([
            $key1,
            $key2,
        ]);

        factory(ControlRule::class)->create([
            'source_id' => $allowedKeyGroup->id,
            'target_id' => $hostGroup->id,
            'action' => 'allow',
        ]);

        $got = $host->getSSHKeysForHost();
        $this->assertCount(1, $got);
        $this->assertArrayHasKey($key1->username, $got);
        $this->assertArrayNotHasKey($key2->username, $got);
    }

    /** @test */
    public function it_returns_the_provided_key_inside_results()
    {
        $host = factory(Host::class)->create();

        $hostGroup = factory(Hostgroup::class)->create();
        $hostGroup->hosts()->save($host);

        $key1 = factory(Key::class)->create(['username' => 'key1']);
        $allowedKeyGroup = factory(Keygroup::class)->create();
        $allowedKeyGroup->keys()->save($key1);

        factory(ControlRule::class)->create([
            'source_id' => $allowedKeyGroup->id,
            'target_id' => $hostGroup->id,
            'action' => 'allow',
        ]);

        $bastionHostKey = factory(Key::class)->create();

        $got = $host->getSSHKeysForHost($bastionHostKey->public);
        $this->assertCount(2, $got);
    }
}
