<?php

namespace Tests\Feature\Http\Api;

use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Tests\ApiTestCase;

class ApiHostsTest extends ApiTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_should_return_many_resources(): void
    {
        $hosts = Host::factory()->count(3)->create();

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->get('/api/v1/hosts');

        $response->assertFetchedMany($hosts);
    }

    /** @test */
    public function it_should_success_when_creating_a_host(): void
    {
        $host = Host::factory()->make();
        $groups = Hostgroup::factory()->count(2)->create();

        $data = [
            'type' => 'hosts',
            'attributes' => [
                'hostname' => $host->hostname,
                'username' => $host->username,
                'port' => $host->port,
                'authorizedKeysFile' => $host->authorized_keys_file,
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

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->withData($data)
            ->includePaths('groups')
            ->post('/api/v1/hosts');

        $id = $response
            ->assertCreatedWithServerId('http://localhost/api/v1/hosts', $data)
            ->id();

        $this->assertDatabaseHas('hosts', [
            'id' => $id,
            'hostname' => $host->hostname,
            'username' => $host->username,
            'port' => $host->port,
            'authorized_keys_file' => $host->authorized_keys_file,
        ]);

        foreach ($groups as $group) {
            $this->assertDatabaseHas('host_hostgroup', [
                'host_id' => $id,
                'hostgroup_id' => $group->getRouteKey(),
            ]);
        }
    }

    /** @test */
    public function it_should_return_error_when_trying_to_create_an_existing_host(): void
    {
        $existingHost = Host::factory()->create();

        $host = Host::factory()->make([
            'hostname' => $existingHost->hostname,
            'username' => $existingHost->username,
        ]);

        $data = [
            'type' => 'hosts',
            'attributes' => [
                'hostname' => $host->hostname,
                'username' => $host->username,
                'port' => $host->port,
                'authorizedKeysFile' => $host->authorized_keys_file,
            ],
        ];

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->withData($data)
            ->post('/api/v1/hosts');

        $response->assertStatusCode(422);
        $response->assertErrorStatus([
            'status' => '422',
        ]);
    }

    /** @test */
    public function it_should_success_when_getting_an_existing_host(): void
    {
        $host = Host::factory()->create();
        $self = 'http://localhost/api/v1/hosts/'.$host->getRouteKey();

        $expected = [
            'type' => 'hosts',
            'id' => (string) $host->getRouteKey(),
            'attributes' => [
                'hostname' => $host->hostname,
                'username' => $host->username,
                'port' => $host->port,
                'fullHostname' => $host->full_hostname,
                'authorizedKeysFile' => $host->authorized_keys_file,
                'enabled' => $host->enabled,
                'createdAt' => $host->created_at->jsonSerialize(),
                'synced' => $host->synced,
                'syncedAt' => optional($host->last_rotation)->jsonSerialize(),
                'syncedStatus' => $host->status_code,
            ],
            'relationships' => [
                'groups' => [
                    'links' => [
                        'self' => "{$self}/relationships/groups",
                        'related' => "{$self}/groups",
                    ],
                ],
            ],
            'links' => [
                'self' => $self,
            ],
        ];

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->get($self);

        $response->assertFetchedOneExact($expected);
    }

    /** @test */
    public function it_should_success_when_updating_a_host(): void
    {
        $host = Host::factory()->create();
        $host->groups()->attach($existing = Hostgroup::factory()->create());

        $newData = Host::factory()->make([
            'hostname' => $host->hostname,
            'username' => $host->username,
        ]);
        $newGroups = Hostgroup::factory()->count(2)->create();
        $data = [
            'type' => 'hosts',
            'id' => (string) $host->getRouteKey(),
            'attributes' => [
                'port' => $newData->port,
                'authorizedKeysFile' => $newData->authorized_keys_file,
                'enabled' => $newData->enabled,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $newGroups->map(fn (Hostgroup $group) => [
                        'type' => 'hostgroups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $expected = [
            'type' => 'hosts',
            'id' => (string) $host->getRouteKey(),
            'attributes' => [
                'hostname' => $host->hostname,
                'username' => $host->username,
                'port' => $newData->port,
                'fullHostname' => $newData->full_hostname,
                'authorizedKeysFile' => $newData->authorized_keys_file,
                'enabled' => $newData->enabled,
                'createdAt' => $host->created_at->jsonSerialize(),
                'synced' => $host->synced,
                'syncedAt' => optional($host->last_rotation)->jsonSerialize(),
                'syncedStatus' => $host->status_code,
            ],
        ];

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->includePaths('groups')
            ->withData($data)
            ->patch('/api/v1/hosts/'.$host->getRouteKey());

        $response->assertFetchedOne($expected);

        /** The modified values should have been changed */
        $this->assertDatabaseHas('hosts', [
            'id' => $host->getKey(),
            'hostname' => $host->hostname,
            'username' => $host->username,
            'port' => $newData->port,
            'authorized_keys_file' => $newData->authorized_keys_file,
            'enabled' => $newData->enabled,
        ]);

        /** The existing group should have been detached. */
        $this->assertDatabaseMissing('host_hostgroup', [
            'host_id' => $host->getKey(),
            'hostgroup_id' => $existing->getKey(),
        ]);

        /** These groups should have been attached. */
        foreach ($newGroups as $group) {
            $this->assertDatabaseHas('host_hostgroup', [
                'host_id' => $host->getKey(),
                'hostgroup_id' => $group->getRouteKey(),
            ]);
        }
    }

    /** @test */
    public function it_should_success_when_deleting_a_host(): void
    {
        $host = Host::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->delete('/api/v1/hosts/'.$host->getRouteKey());

        $response->assertNoContent();

        $this->assertDatabaseMissing('hosts', [
            'id' => $host->getKey(),
        ]);
    }
}
