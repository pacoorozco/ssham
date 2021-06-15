<?php

namespace Tests;

use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
    }

    /**
     * Make ajax GET request.
     *
     * @param  string  $uri
     *
     * @return TestResponse
     */
    protected function ajaxGet(string $uri): TestResponse
    {
        return $this->withHeader('HTTP_X-Requested-With', 'XMLHttpRequest')
            ->get($uri);
    }
}
