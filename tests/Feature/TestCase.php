<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * Make ajax GET request.
     *
     * @param  string  $uri
     * @return TestResponse
     */
    protected function ajaxGet(string $uri): TestResponse
    {
        return $this
            ->withHeader('HTTP_X-Requested-With', 'XMLHttpRequest')
            ->get($uri);
    }
}
