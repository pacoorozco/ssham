<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Make ajax GET request.
     *
     * @param string $uri
     * @return TestResponse
     */
    protected function ajaxGet(string $uri): TestResponse
    {
        return $this
            ->withHeader('HTTP_X-Requested-With', 'XMLHttpRequest')
            ->get($uri);
    }
}
