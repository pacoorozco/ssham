<?php

declare(strict_types=1);

namespace App\Services\Pusher;

use phpseclib3\Net\SFTP;

interface ConnectionProvider
{
    public function provideConnection(): SFTP;
}
