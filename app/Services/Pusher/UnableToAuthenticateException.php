<?php

declare(strict_types=1);

namespace App\Services\Pusher;

use RuntimeException;

class UnableToAuthenticateException extends RuntimeException
{
    public static function withPrivateKey(): UnableToAuthenticateException
    {
        return new UnableToAuthenticateException('Unable to authenticate using a private key.');
    }
}
