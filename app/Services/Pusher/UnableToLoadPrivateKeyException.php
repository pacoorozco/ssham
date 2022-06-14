<?php

declare(strict_types=1);

namespace App\Services\Pusher;

use RuntimeException;

class UnableToLoadPrivateKeyException extends RuntimeException
{
    public function __construct(string $message = 'Unable to load private key.')
    {
        parent::__construct($message);
    }
}
