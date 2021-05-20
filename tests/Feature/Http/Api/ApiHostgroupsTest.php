<?php

namespace Tests\Feature\Http\Api;

use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Tests\ApiTestCase;

class ApiHostgroupsTest extends ApiTestCase
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
        $groups = Hostgroup::factory()->count(3)->create();

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->get('/api/v1/hostgroups');

        $response->assertFetchedMany($groups);
    }

    /** @test */
    public function it_should_success_when_creating_a_hostgroup(): void
    {
        $group = Hostgroup::factory()->make();
        $hosts = Host::factory()->count(2)->create();

        $data = [
            'type' => 'hostgroups',
            'attributes' => [
                'name' => $group->name,
                'description' => $group->description,
            ],
            'relationships' => [
                'hosts' => [
                    'data' => $hosts->map(fn (Host $host) => [
                        'type' => 'hosts',
                        'id' => (string) $host->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->withData($data)
            ->includePaths('hosts')
            ->post('/api/v1/hostgroups');

        $id = $response
            ->assertCreatedWithServerId('http://localhost/api/v1/hostgroups', $data)
            ->id();

        $this->assertDatabaseHas('hostgroups', [
            'id' => $id,
            'name' => $group->name,
            'description' => $group->description,
        ]);

        foreach ($hosts as $host) {
            $this->assertDatabaseHas('host_hostgroup', [
                'host_id' => $host->getRouteKey(),
                'hostgroup_id' => $id,
            ]);
        }
    }

    /** @test */
    public function it_should_return_error_when_trying_to_create_an_existing_hostgroup(): void
    {
        $existingGroup = Hostgroup::factory()->create();

        $group = Hostgroup::factory()->make([
            'name' => $existingGroup->name,
        ]);

        $data = [
            'type' => 'hostgroups',
            'attributes' => [
                'name' => $group->name,
                'description' => $group->description,
            ],
        ];

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->withData($data)
            ->post('/api/v1/hostgroups');

        $response->assertStatusCode(422);
        $response->assertErrorStatus([
            'status' => '422',
        ]);
    }

    /** @test */
    public function it_should_success_when_getting_an_existing_hostgroup(): void
    {
        $group = Hostgroup::factory()->create();
        $self = 'http://localhost/api/v1/hostgroups/'.$group->getRouteKey();

        $expected = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'name' => $group->name,
                'description' => $group->description,
                'createdAt' => $group->created_at->jsonSerialize(),
            ],
            'relationships' => [
                'hosts' => [
                    'links' => [
                        'self' => "{$self}/relationships/hosts",
                        'related' => "{$self}/hosts",
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
            ->expects('hostgroups')
            ->get($self);

        $response->assertFetchedOneExact($expected);
    }

    /** @test */
    public function it_should_success_when_updating_a_hostgroup(): void
    {
        $group = Hostgroup::factory()->create();
        $group->hosts()->attach($existing = Host::factory()->create());

        $newData = Hostgroup::factory()->make([
            'name' => $group->name,
        ]);
        $newHosts = Host::factory()->count(2)->create();
        $data = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'description' => $newData->description,
            ],
            'relationships' => [
                'hosts' => [
                    'data' => $newHosts->map(fn (Host $host) => [
                        'type' => 'hosts',
                        'id' => (string) $host->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $expected = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'name' => $group->name,
                'description' => $newData->description,
                'createdAt' => $group->created_at->jsonSerialize(),
            ],
        ];

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->includePaths('hosts')
            ->withData($data)
            ->patch('/api/v1/hostgroups/'.$group->getRouteKey());

        $response->assertFetchedOne($expected);

        /** The modified values should have been changed */
        $this->assertDatabaseHas('hostgroups', [
            'id' => $group->getKey(),
            'name' => $group->name,
            'description' => $newData->description,
        ]);

        /** The existing host should have been detached. */
        $this->assertDatabaseMissing('host_hostgroup', [
            'host_id' => $existing->getKey(),
            'hostgroup_id' => $group->getKey(),
        ]);

        /** These hosts should have been attached. */
        foreach ($newHosts as $host) {
            $this->assertDatabaseHas('host_hostgroup', [
                'host_id' => $host->getKey(),
                'hostgroup_id' => $group->getRouteKey(),
            ]);
        }
    }

    /** @test */
    public function it_should_success_when_deleting_a_host(): void
    {
        $group = Hostgroup::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->delete('/api/v1/hostgroups/'.$group->getRouteKey());

        $response->assertNoContent();

        $this->assertDatabaseMissing('hostgroups', [
            'id' => $group->getKey(),
        ]);
    }
}
