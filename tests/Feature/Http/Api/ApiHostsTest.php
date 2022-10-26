<?php

namespace Tests\Feature\Http\Api;

use App\Enums\Permissions;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Generator;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Tests\Traits\InteractsWithPermissions;

class ApiHostsTest extends ApiTestCase
{
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function users_should_not_see_any_resource(): void
    {
        Host::factory()->count(2)->create();

        $this
            ->actingAs($this->user)
            ->get(route('v1.hosts.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_resources(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $hosts = Host::factory()->count(3)->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->get(route('v1.hosts.index'))
            ->assertFetchedMany($hosts);
    }

    /** @test */
    public function users_should_not_create_hosts(): void
    {
        /** @var Host $want */
        $want = Host::factory()->make();

        $data = [
            'type'       => 'hosts',
            'attributes' => [
                'hostname' => $want->hostname,
                'username' => $want->username,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->withData($data)
            ->post(route('v1.hosts.store'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_create_hosts(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $groups = Hostgroup::factory()
            ->count(2)
            ->create();

        $data = [
            'type'       => 'hosts',
            'attributes' => [
                'hostname'           => $want->hostname,
                'username'           => $want->username,
                'port'               => $want->port,
                'authorizedKeysFile' => $want->authorized_keys_file,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn (Hostgroup $group) => [
                        'type' => 'hostgroups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $id = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->withData($data)
            ->includePaths('groups')
            ->post(route('v1.hosts.store'))
            ->assertCreatedWithServerId('http://localhost/api/v1/hosts', $data)
            ->id();

        $host = Host::query()
            ->where('id', $id)
            ->where('hostname', $want->hostname)
            ->where('username', $want->username)
            ->where('port', $want->port)
            ->where('authorized_keys_file', $want->authorized_keys_file)
            ->first();

        $this->assertInstanceOf(Host::class, $host);

        $this->assertEquals($groups->pluck('id'), $host->groups->pluck('id'));
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostCreation
     */
    public function editors_should_get_errors_when_creating_hosts_with_wrong_data(
        array $input,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Host to validate unique rules...
        Host::factory()->create([
            'hostname'             => 'foo',
            'username'             => 'bar',
            'authorized_keys_file' => 'already-created-host',
        ]);

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $data = [
            'type'       => 'hosts',
            'attributes' => [
                'hostname'           => $input['hostname'] ?? $want->hostname,
                'username'           => $input['username'] ?? $want->username,
                'enabled'            => $input['enabled'] ?? $want->enabled,
                'port'               => $input['port'] ?? $want->port,
                'authorizedKeysFile' => $input['authorizedKeysFile'] ?? $want->authorized_keys_file,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->withData($data)
            ->expects('hosts')
            ->post(route('v1.hosts.store'))
            ->assertError(Response::HTTP_UNPROCESSABLE_ENTITY, $errors);

        $this->assertDatabaseMissing(Host::class, [
            'hostname'             => Arr::get($data, 'attributes.hostname'),
            'username'             => Arr::get($data, 'attributes.username'),
            'enabled'              => Arr::get($data, 'attributes.enabled'),
            'port'                 => Arr::get($data, 'attributes.port'),
            'authorized_keys_file' => Arr::get($data, 'attributes.authorizedKeysFile'),
        ]);
    }

    public function provideWrongDataForHostCreation(): Generator
    {
        yield 'hostname is empty' => [
            'input' => [
                'hostname' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/hostname'],
            ],
        ];

        yield 'hostname > 255 chars' => [
            'input' => [
                'hostname' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/hostname'],
            ],
        ];

        yield 'username is empty' => [
            'input' => [
                'username' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/username'],
            ],
        ];

        yield 'username > 255 chars' => [
            'input' => [
                'username' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/username'],
            ],
        ];

        yield 'hostname & username is taken' => [
            'input' => [
                'hostname' => 'foo',
                'username' => 'bar',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/hostname'],
            ],
        ];

        yield 'port ! valid' => [
            'input' => [
                'port' => 'non-integer',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/port'],
            ],
        ];

        yield 'enabled ! valid' => [
            'input' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/enabled'],
            ],
        ];

        yield 'authorized_keys_file is empty' => [
            'input' => [
                'authorizedKeysFile' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/authorizedKeysFile'],
            ],
        ];

        yield 'authorized_keys_file > 255 chars' => [
            'input' => [
                'authorizedKeysFile' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/authorizedKeysFile'],
            ],
        ];
    }

    /** @test */
    public function users_should_not_see_a_resource(): void
    {
        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->get(route('v1.hosts.show', $host))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_a_resource(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        /** @var Host $host */
        $host = Host::factory()
            ->customized()
            ->create();

        $self = 'http://localhost/api/v1/hosts/'.$host->getRouteKey();

        $expected = [
            'type'       => 'hosts',
            'id'         => (string) $host->getRouteKey(),
            'attributes' => [
                'hostname'           => $host->hostname,
                'username'           => $host->username,
                'port'               => $host->port,
                'fullHostname'       => $host->full_hostname,
                'authorizedKeysFile' => $host->authorized_keys_file,
                'enabled'            => $host->enabled,
                'createdAt'          => $host->created_at->jsonSerialize(),
                'synced'             => false,
                'syncedAt'           => optional($host->last_rotation)->jsonSerialize(),
                'syncedStatus'       => $host->status_code->description,
            ],
            'relationships' => [
                'groups' => [
                    'links' => [
                        'self'    => "{$self}/relationships/groups",
                        'related' => "{$self}/groups",
                    ],
                ],
            ],
            'links' => [
                'self' => $self,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->get(route('v1.hosts.show', $host))
            ->assertFetchedOne($expected);
    }

    /** @test */
    public function users_should_not_update_hosts(): void
    {
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $data = [
            'type'       => 'hosts',
            'id'         => (string) $host->getRouteKey(),
            'attributes' => [
                'port'               => $want->port,
                'authorizedKeysFile' => $want->authorized_keys_file,
                'enabled'            => $want->enabled,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->withData($data)
            ->patch(route('v1.hosts.update', $host))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_update_hosts(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Create some groups to test the membership.
        $groups = Hostgroup::factory()
            ->count(2)
            ->create();

        /** @var Host $host */
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $data = [
            'type'       => 'hosts',
            'id'         => (string) $host->getRouteKey(),
            'attributes' => [
                'port'               => $want->port,
                'authorizedKeysFile' => $want->authorized_keys_file,
                'enabled'            => $want->enabled,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn (Hostgroup $group) => [
                        'type' => 'hostgroups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $expected = [
            'type'       => 'hosts',
            'id'         => (string) $host->getRouteKey(),
            'attributes' => [
                'hostname'           => $host->hostname,
                'username'           => $host->username,
                'port'               => $want->port,
                'fullHostname'       => $host->full_hostname,
                'authorizedKeysFile' => $want->authorized_keys_file,
                'enabled'            => $want->enabled,
                'createdAt'          => $host->created_at->jsonSerialize(),
                'synced'             => false,
                'syncedAt'           => $host->last_rotation?->jsonSerialize(),
                'syncedStatus'       => $host->status_code->description,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->includePaths('groups')
            ->withData($data)
            ->patch(route('v1.hosts.update', $host))
            ->assertFetchedOne($expected);

        $host = Host::query()
            ->where('id', $host->id)
            ->where('hostname', $host->hostname)
            ->where('username', $host->username)
            ->where('port', $want->port)
            ->where('authorized_keys_file', $want->authorized_keys_file)
            ->where('enabled', $want->enabled)
            ->first();

        $this->assertInstanceOf(Host::class, $host);

        $this->assertEquals($groups->pluck('id'), $host->groups->pluck('id'));
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostModification
     */
    public function editors_should_get_errors_when_updating_hosts_with_wrong_data(
        array $input,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Host to validate unique rules...
        Host::factory()->create([
            'hostname'             => 'foo',
            'username'             => 'bar',
            'authorized_keys_file' => 'already-created-host',
        ]);

        /** @var Host $host */
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $data = [
            'type'       => 'hosts',
            'id'         => (string) $host->getRouteKey(),
            'attributes' => [
                'hostname'           => $input['hostname'] ?? $want->hostname,
                'username'           => $input['username'] ?? $want->username,
                'enabled'            => $input['enabled'] ?? $want->enabled,
                'port'               => $input['port'] ?? $want->port,
                'authorizedKeysFile' => $input['authorizedKeysFile'] ?? $want->authorized_keys_file,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->withData($data)
            ->expects('hosts')
            ->patch(route('v1.hosts.update', $host))
            ->assertError(Response::HTTP_UNPROCESSABLE_ENTITY, $errors);

        $this->assertDatabaseMissing(Host::class, [
            'hostname'             => Arr::get($data, 'attributes.hostname'),
            'username'             => Arr::get($data, 'attributes.username'),
            'enabled'              => Arr::get($data, 'attributes.enabled'),
            'port'                 => Arr::get($data, 'attributes.port'),
            'authorized_keys_file' => Arr::get($data, 'attributes.authorizedKeysFile'),
        ]);
    }

    public function provideWrongDataForHostModification(): Generator
    {
        yield 'hostname is empty' => [
            'input' => [
                'hostname' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/hostname'],
            ],
        ];

        yield 'hostname > 255 chars' => [
            'input' => [
                'hostname' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/hostname'],
            ],
        ];

        yield 'username is empty' => [
            'input' => [
                'username' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/username'],
            ],
        ];

        yield 'username > 255 chars' => [
            'input' => [
                'username' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/username'],
            ],
        ];

        yield 'hostname & username is taken' => [
            'input' => [
                'hostname' => 'foo',
                'username' => 'bar',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/hostname'],
            ],
        ];

        yield 'port ! valid' => [
            'input' => [
                'port' => 'non-integer',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/port'],
            ],
        ];

        yield 'enabled ! valid' => [
            'input' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/enabled'],
            ],
        ];

        yield 'authorized_keys_file is empty' => [
            'input' => [
                'authorizedKeysFile' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/authorizedKeysFile'],
            ],
        ];

        yield 'authorized_keys_file > 255 chars' => [
            'input' => [
                'authorizedKeysFile' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/authorizedKeysFile'],
            ],
        ];
    }

    /** @test */
    public function users_should_not_delete_hosts(): void
    {
        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->delete(route('v1.hosts.destroy', $host))
            ->assertForbidden();

        $this->assertModelExists($host);
    }

    /** @test */
    public function eliminators_should_delete_hosts(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteHosts);

        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->delete(route('v1.hosts.destroy', $host))
            ->assertNoContent();

        $this->assertModelMissing($host);
    }
}
