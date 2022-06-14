<?php

declare(strict_types=1);

namespace App\Services\Pusher;

use RuntimeException;

class UnableToConnectToSftpHostException extends RuntimeException
{
    public static function atHostname(string $host): UnableToConnectToSftpHostException
    {
        return new UnableToConnectToSftpHostException("Unable to connect to host: $host");
    }
}
