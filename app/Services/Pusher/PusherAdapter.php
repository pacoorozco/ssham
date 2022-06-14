<?php

declare(strict_types=1);

namespace App\Services\Pusher;

interface PusherAdapter
{
    /**
     * @throws \App\Exceptions\PusherException
     */
    public function write(string $path, string $contents, int $permission): void;

    /**
     * @throws \App\Exceptions\PusherException
     */
    public function exec(string $command): void;
}
