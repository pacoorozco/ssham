<?php

namespace Tests\Feature\Models;

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use Generator;
use Tests\Feature\TestCase;

class HostTest extends TestCase
{
    /** @test */
    public function it_should_return_the_enabled_hosts(): void
    {
        Host::factory()
            ->count(2)
            ->disabled()
            ->create();

        $want = Host::factory()
            ->count(3)
            ->enabled()
            ->create()
            ->pluck(['id', 'username', 'hostname']);

        $got = Host::enabled()
            ->get()
            ->pluck(['id', 'username', 'hostname']);

        $this->assertEquals($want, $got);
    }

    /** @test */
    public function it_should_not_return_the_enabled_hosts_if_there_are_not(): void
    {
        Host::factory()
            ->count(2)
            ->disabled()
            ->create();

        $this->assertEmpty(Host::enabled()->get());
    }

    /** @test */
    public function it_should_return_the_hosts_that_has_pending_changes(): void
    {
        Host::factory()
            ->count(2)
            ->withoutPendingChanges()
            ->create();

        $want = Host::factory()
            ->count(3)
            ->withPendingChanges()
            ->create()
            ->pluck(['id', 'username', 'hostname']);

        $got = Host::withPendingChanges()
            ->get()
            ->pluck(['id', 'username', 'hostname']);

        $this->assertEquals($want, $got);
    }

    /** @test */
    public function it_should_not_return_hosts_with_pending_changes_if_there_are_not(): void
    {
        Host::factory()
            ->count(2)
            ->withoutPendingChanges()
            ->create();

        $this->assertEmpty(Host::withPendingChanges()->get());
    }

    /** @test */
    public function it_returns_the_expected_number_of_keys_for_a_given_host(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        // These two keys should appear on the getSSHKeysForHost() list.
        $key1 = Key::factory()->create(['name' => 'key1']);
        $key2 = Key::factory()->create(['name' => 'key2']);
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
        $this->assertArrayHasKey($key1->name, $got);
        $this->assertArrayHasKey($key2->name, $got);
        $this->assertArrayNotHasKey($key3->name, $got);
    }

    /** @test */
    public function it_returns_empty_array_when_key_is_allowed_and_denied_for_a_given_host(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        $key1 = Key::factory()->create(['name' => 'key1']);
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
        $key1 = Key::factory()->create(['name' => 'key1']);
        // This key should NOT appear, because is disabled, on the getSSHKeysForHost() list.
        $key2 = Key::factory()->create(['name' => 'key2', 'enabled' => false]);
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
        $this->assertArrayHasKey($key1->name, $got);
        $this->assertArrayNotHasKey($key2->name, $got);
    }

    /** @test */
    public function it_returns_the_provided_key_inside_results(): void
    {
        $host = Host::factory()->create();

        $hostGroup = Hostgroup::factory()->create();
        $hostGroup->hosts()->save($host);

        $key1 = Key::factory()->create(['name' => 'key1']);
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
    public function it_should_return_the_host_full_name(): void
    {
        /** @var Host $host */
        $host = Host::factory()->make([
            'username' => 'root',
            'hostname' => 'server1.domain.local',
        ]);

        $want = 'root@server1.domain.local';

        $this->assertEquals($want, $host->full_hostname);
    }

    const SSH_PORT_DEFAULT_VALUE = 22;

    /**
     * @test
     * @dataProvider provideGetPortTestCases
     */
    public function it_should_return_the_host_port(
        array $attributes,
        int $want,
    ): void {
        // Set the default value.
        setting()->set('ssh_port', self::SSH_PORT_DEFAULT_VALUE);

        /** @var Host $host */
        $host = Host::factory()->make([
            'port' => $attributes['port'],
        ]);

        $this->assertEquals($want, $host->portOrDefaultSetting());
    }

    public static function provideGetPortTestCases(): Generator
    {
        yield 'custom value as int, should return the provided value' => [
            'attributes' => [
                'port' => 2022,
            ],
            'want' => 2022,
        ];

        yield 'custom value as string, should return the provided value' => [
            'attributes' => [
                'port' => '2022',
            ],
            'want' => 2022,
        ];

        yield 'empty value, should return the default setting' => [
            'attributes' => [
                'port' => '',
            ],
            'want' => self::SSH_PORT_DEFAULT_VALUE,
        ];

        yield 'null value, should return the default setting' => [
            'attributes' => [
                'port' => null,
            ],
            'want' => self::SSH_PORT_DEFAULT_VALUE,
        ];

        yield 'value of 0,  should return the provided value' => [
            'attributes' => [
                'port' => 0,
            ],
            'want' => self::SSH_PORT_DEFAULT_VALUE,
        ];

        yield 'invalid value, should return the provided value' => [
            'attributes' => [
                'port' => 'foo',
            ],
            'want' => self::SSH_PORT_DEFAULT_VALUE,
        ];
    }

    const AUTHORIZED_KEYS_DEFAULT_VALUE = '~/.ssh/authorized_keys_file';

    /**
     * @test
     * @dataProvider provideGetAuthorizedKeysFileTestCases
     */
    public function it_should_return_the_authorized_keys_file(
        array $attributes,
        string $want,
    ): void {
        // Set the default value.
        setting()->set('authorized_keys', self::AUTHORIZED_KEYS_DEFAULT_VALUE);

        /** @var Host $host */
        $host = Host::factory()->make([
            'authorized_keys_file' => $attributes['authorized_keys_file'],
        ]);

        $this->assertEquals($want, $host->authorizedKeysFileOrDefaultSetting());
    }

    public static function provideGetAuthorizedKeysFileTestCases(): Generator
    {
        yield 'custom value, should return the provided value' => [
            'attributes' => [
                'authorized_keys_file' => 'my-custom-path',
            ],
            'want' => 'my-custom-path',
        ];

        yield 'empty value, should return the default setting' => [
            'attributes' => [
                'authorized_keys_file' => '',
            ],
            'want' => self::AUTHORIZED_KEYS_DEFAULT_VALUE,
        ];

        yield 'null value, should return the default setting' => [
            'attributes' => [
                'authorized_keys_file' => null,
            ],
            'want' => self::AUTHORIZED_KEYS_DEFAULT_VALUE,
        ];
    }

    /** @test */
    public function it_should_return_lowercase_username(): void
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

    public static function provideUsernameTestCases(): Generator
    {
        yield 'User, should be user' => [
            'input' => 'User',
            'want' => 'user',
        ];
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
