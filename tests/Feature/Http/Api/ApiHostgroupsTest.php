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

class ApiHostgroupsTest extends ApiTestCase
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
        Hostgroup::factory()->count(2)->create();

        $this
            ->actingAs($this->user)
            ->get(route('v1.hostgroups.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_resources(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $groups = Hostgroup::factory()->count(3)->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->get(route('v1.hostgroups.index'))
            ->assertFetchedMany($groups);
    }

    /** @test */
    public function users_should_not_create_hosts_groups(): void
    {
        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $data = [
            'type' => 'hostgroups',
            'attributes' => [
                'name' => $want->name,
                'description' => $want->description,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->withData($data)
            ->post(route('v1.hostgroups.store'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_create_hosts_groups(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $hosts = Host::factory()
            ->count(2)
            ->create();

        $data = [
            'type' => 'hostgroups',
            'attributes' => [
                'name' => $want->name,
                'description' => $want->description,
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

        $id = $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->withData($data)
            ->includePaths('hosts')
            ->post(route('v1.hostgroups.store'))
            ->assertCreatedWithServerId('http://localhost/api/v1/hostgroups', $data)
            ->id();

        $group = Hostgroup::query()
            ->where('id', $id)
            ->where('name', $want->name)
            ->where('description', $want->description)
            ->first();

        $this->assertInstanceOf(Hostgroup::class, $group);

        $this->assertCount(count($hosts), $group->hosts);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForGroupCreation
     */
    public function editors_should_get_errors_when_creating_groups_with_wrong_data(
        array $input,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Group to validate unique rules...
        Hostgroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $data = [
            'type' => 'hostgroups',
            'attributes' => [
                'name' => $input['name'] ?? $want->name,
                'description' => $input['description'] ?? $want->description,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->withData($data)
            ->post(route('v1.hostgroups.store'))->assertError(Response::HTTP_UNPROCESSABLE_ENTITY, $errors);

        $this->assertDatabaseMissing(Hostgroup::class, [
            'name' => Arr::get($data, 'attributes.name'),
            'description' => Arr::get($data, 'attributes.description'),
        ]);
    }

    public function provideWrongDataForGroupCreation(): Generator
    {
        yield 'name is empty' => [
            'input' => [
                'name' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];

        yield 'name < 5 chars' => [
            'input' => [
                'name' => 'foo',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];

        yield 'name > 255 chars' => [
            'input' => [
                'name' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];

        yield 'name is taken' => [
            'input' => [
                'name' => 'foo',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];
    }

    /** @test */
    public function users_should_not_see_a_resource(): void
    {
        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hosts')
            ->get(route('v1.hostgroups.show', $group))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_a_resource(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        /** @var Hostgroup $group */
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

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->get(route('v1.hostgroups.show', $group))
            ->assertFetchedOneExact($expected);
    }

    /** @test */
    public function users_should_not_update_hosts_groups(): void
    {
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()
            ->make();

        $data = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'description' => $want->description,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->withData($data)
            ->patch(route('v1.hostgroups.update', $group))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_update_hosts_groups(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Create some hosts to test the membership.
        $hosts = Host::factory()
            ->count(2)
            ->create();

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $data = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'description' => $want->description,
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

        $expected = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'name' => $group->name,
                'description' => $want->description,
                'createdAt' => $group->created_at->jsonSerialize(),
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->includePaths('hosts')
            ->withData($data)
            ->patch(route('v1.hostgroups.update', $group))
            ->assertFetchedOne($expected);

        $group = Hostgroup::query()
            ->where('id', $group->id)
            ->where('name', $group->name)
            ->where('description', $want->description)
            ->first();

        $this->assertInstanceOf(Hostgroup::class, $group);

        $this->assertCount(count($hosts), $group->hosts);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForGroupModification
     */
    public function editors_should_get_errors_when_updating_groups_with_wrong_data(
        array $input,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Group to validate unique rules...
        Hostgroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $data = [
            'type' => 'hostgroups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'name' => $input['name'] ?? $want->name,
                'description' => $input['description'] ?? $want->description,
            ],
        ];

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->expects('hostgroups')
            ->includePaths('hosts')
            ->withData($data)
            ->patch(route('v1.hostgroups.update', $group))
            ->assertError(Response::HTTP_UNPROCESSABLE_ENTITY, $errors);

        $this->assertDatabaseMissing(Hostgroup::class, [
            'id' => $group->id,
            'name' => Arr::get($data, 'attributes.name'),
            'description' => Arr::get($data, 'attributes.description'),
        ]);

        $group->refresh();

        $this->assertCount(0, $group->hosts);
    }

    public function provideWrongDataForGroupModification(): Generator
    {
        yield 'name is empty' => [
            'data' => [
                'name' => '',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];

        yield 'name < 5 chars' => [
            'data' => [
                'name' => 'foo',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];

        yield 'name > 255 chars' => [
            'data' => [
                'name' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];

        yield 'name is taken' => [
            'data' => [
                'name' => 'foo',
            ],
            'errors' => [
                'source' => ['pointer' => '/data/attributes/name'],
            ],
        ];
    }

    /** @test */
    public function users_should_not_delete_hosts_groups(): void
    {
        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->delete(route('v1.hostgroups.destroy', $group))
            ->assertForbidden();

        $this->assertModelExists($group);
    }

    /** @test */
    public function eliminators_should_delete_hosts_groups(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteHosts);

        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->jsonApi()
            ->delete(route('v1.hostgroups.destroy', $group))
            ->assertNoContent();

        $this->assertModelMissing($group);
    }
}
