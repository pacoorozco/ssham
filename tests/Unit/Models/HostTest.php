<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use App\Enums\HostStatus;
use App\Models\Host;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\Unit\ModelTestCase;

final class HostTest extends ModelTestCase
{
    #[Test]
    public function contains_valid_fillable_properties(): void
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

    #[Test]
    public function contains_valid_casts_properties(): void
    {
        $m = new Host();
        $this->assertEquals([
            'id' => 'int',
            'enabled' => 'boolean',
            'synced' => 'boolean',
            'last_rotation' => 'datetime',
            'status_code' => HostStatus::class,
            'port' => 'int',
        ], $m->getCasts());
    }

    #[Test]
    public function has_groups_relation(): void
    {
        $m = new Host();
        $r = $m->groups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    #[Test]
    #[DataProvider('provideUsernameTestCases')]
    public function it_should_return_lowercase_usernames(
        string $input,
        string $want,
    ): void {
        /** @var Host $host */
        $host = Host::factory()->make([
            'username' => $input,
        ]);

        $this->assertEquals($want, $host->username);
    }

    public static function provideUsernameTestCases(): \Generator
    {
        yield 'user, should be user' => [
            'input' => 'user',
            'want' => 'user',
        ];

        yield 'User, should be user' => [
            'input' => 'User',
            'want' => 'user',
        ];

        yield 'ADMIN, should be admin' => [
            'input' => 'ADMIN',
            'want' => 'admin',
        ];

        yield 'Adm1n, should be adm1n' => [
            'input' => 'Adm1n',
            'want' => 'adm1n',
        ];
    }

    #[Test]
    #[DataProvider('provideHostnameTestCases')]
    public function it_should_return_lowercase_hostnames(
        string $input,
        string $want,
    ): void {
        /** @var Host $host */
        $host = Host::factory()->makeOne([
            'hostname' => $input,
        ]);

        $this->assertEquals($want, $host->hostname);
    }

    public static function provideHostnameTestCases(): \Generator
    {
        yield 'lowercase input' => [
            'input' => 'server.domain.local',
            'want' => 'server.domain.local',
        ];

        yield 'uppercase input' => [
            'input' => 'SERVER',
            'want' => 'server',
        ];

        yield 'mixed input' => [
            'input' => 'SERVER.domain.LOCAL',
            'want' => 'server.domain.local',
        ];

        yield 'mixed input with numbers' => [
            'input' => 'SERVER12.domain.LOCAL',
            'want' => 'server12.domain.local',
        ];
    }
}
