<?php

namespace Tests\Feature\Models;

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function scopeEnabled_returns_correct_data(): void
    {
        // two disabled Host
        Host::factory()
            ->count(2)
            ->create([
                'enabled' => false,
            ]);

        // three enabled Host
        Host::factory()
            ->count(3)
            ->create([
                'enabled' => true,
            ]);

        $got = Host::enabled()->get();

        $this->assertCount(3, $got);
    }

    /** @test */
    public function scopeEnabled_returns_no_data_if_all_hosts_are_disabled(): void
    {
        // two disabled Host
        Host::factory()
            ->count(2)
            ->create([
                'enabled' => false,
            ]);

        $got = Host::enabled()->get();

        $this->assertEmpty($got);
    }

    /** @test */
    public function scopeNotInSync_returns_correct_data(): void
    {
        // two synced Host
        Host::factory()
            ->count(2)
            ->create([
                'synced' => true,
            ]);

        // three not in sync Host
        Host::factory()
            ->count(3)
            ->create([
                'synced' => false,
            ]);

        $got = Host::notInSync()->get();

        $this->assertCount(3, $got);
    }

    /** @test */
    public function scopeNotInSync_returns_no_data_if_all_hosts_are_disabled(): void
    {
        // two synced Host
        Host::factory()
            ->count(2)
            ->create([
                'synced' => true,
            ]);

        $got = Host::notInSync()->get();

        $this->assertEmpty($got);
    }

    /** @test */
    public function it_returns_the_expected_number_of_keys_for_a_given_host(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        // These two keys should appear on the getSSHKeysForHost() list.
        $key1 = Key::factory()->create(['username' => 'key1']);
        $key2 = Key::factory()->create(['username' => 'key2']);
        $allowedKeyGroup = Keygroup::factory()->create();
        $allowedKeyGroup->keys()->saveMany([
            $key1,
            $key2,
        ]);

        ControlRule::factory()
            ->create([
                'source_id' => $allowedKeyGroup->id,
                'target_id' => $hostGroup->id,
                'action' => ControlRuleAction::Allow,
            ]);

        // This key should NOT appear on the getSSHKeysForHost() list.
        $key3 = Key::factory()->create();
        $deniedKeyGroup = Keygroup::factory()->create();
        $deniedKeyGroup->keys()->save(
            $key3
        );

        ControlRule::factory()
            ->create([
                'source_id' => $deniedKeyGroup->id,
                'target_id' => $hostGroup->id,
                'action' => ControlRuleAction::Deny,
            ]);

        $got = $host->getSSHKeysForHost();
        $this->assertCount(2, $got);
        $this->assertArrayHasKey($key1->username, $got);
        $this->assertArrayHasKey($key2->username, $got);
        $this->assertArrayNotHasKey($key3->username, $got);
    }

    /** @test */
    public function it_returns_empty_array_when_key_is_allowed_and_denied_for_a_given_host(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        $key1 = Key::factory()->create(['username' => 'key1']);
        $allowedKeyGroup = Keygroup::factory()->create();
        $allowedKeyGroup->keys()->save($key1);

        ControlRule::factory()
            ->create([
                'source_id' => $allowedKeyGroup->id,
                'target_id' => $hostGroup->id,
                'action' => ControlRuleAction::Allow,
            ]);

        $deniedKeyGroup = Keygroup::factory()->create();
        $deniedKeyGroup->keys()->save($key1);

        ControlRule::factory()
            ->create([
                'source_id' => $deniedKeyGroup->id,
                'target_id' => $hostGroup->id,
                'action' => ControlRuleAction::Deny,
            ]);

        $got = $host->getSSHKeysForHost();
        $this->assertCount(0, $got);
    }

    /** @test */
    public function it_returns_the_expected_number_of_keys_when_key_is_disabled(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        // This key should appear on the getSSHKeysForHost() list.
        $key1 = Key::factory()->create(['username' => 'key1']);
        // This key should NOT appear, because is disabled, on the getSSHKeysForHost() list.
        $key2 = Key::factory()->create(['username' => 'key2', 'enabled' => false]);
        $allowedKeyGroup = Keygroup::factory()->create();
        $allowedKeyGroup->keys()->saveMany([
            $key1,
            $key2,
        ]);

        ControlRule::factory()
            ->create([
                'source_id' => $allowedKeyGroup->id,
                'target_id' => $hostGroup->id,
                'action' => ControlRuleAction::Allow,
            ]);

        $got = $host->getSSHKeysForHost();
        $this->assertCount(1, $got);
        $this->assertArrayHasKey($key1->username, $got);
        $this->assertArrayNotHasKey($key2->username, $got);
    }

    /** @test */
    public function it_returns_the_provided_key_inside_results(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        $key1 = Key::factory()->create(['username' => 'key1']);
        $allowedKeyGroup = Keygroup::factory()->create();
        $allowedKeyGroup->keys()->save($key1);

        ControlRule::factory()
            ->create([
                'source_id' => $allowedKeyGroup->id,
                'target_id' => $hostGroup->id,
                'action' => ControlRuleAction::Allow,
            ]);

        $bastionHostKey = Key::factory()->create();

        $got = $host->getSSHKeysForHost($bastionHostKey->public);
        $this->assertCount(2, $got);
    }

    /** @test */
    public function getFullHostname_return_the_full_hostname(): void
    {
        /** @var Host $host */
        $host = Host::factory()->makeOne([
            'username' => 'root',
            'hostname' => 'server1.domain.local',
            'port' => 12345,
        ]);
        $want = 'root@server1.domain.local';

        $this->assertEquals($want, $host->full_hostname);
    }

    /**
     * @test
     * @dataProvider provideGetPortOrDefaultTestCases
     */
    public function getPortOrDefault_return_the_port(
        string $port,
        int $want,
    ): void {

        // Sets the default port.
        setting()->set('ssh_port', 22);

        /** @var Host $host */
        $host = Host::factory()->make([
            'port' => $port,
        ]);

        $this->assertEquals($want, $host->getPortOrDefault());
    }

    public function provideGetPortOrDefaultTestCases(): \Generator
    {
        yield "custom port" => [
            'port' => '2022',
            'want' => 2022,
        ];

        yield "default port" => [
            'port' => '0',
            'want' => 22,
        ];

        yield "port is empty" => [
            'port' => '',
            'want' => 22,
        ];
    }
}
