<?php

declare(strict_types=1);

namespace App\Services\Pusher;

use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Exception\NoKeyLoadedException;
use phpseclib3\Net\SFTP;
use Throwable;

class SFTPConnectionProvider implements ConnectionProvider
{
    private ?SFTP $connection;

    public function __construct(
        private string $host,
        private string $username,
        private string $privateKey,
        private int $port = 22,
        private int $timeout = 10,
        private int $maxTries = 3,
    ) {
    }

    public function provideConnection(): SFTP
    {
        $tries = 0;
        start:

        $connection = $this->connection instanceof SFTP
            ? $this->connection
            : $this->setupConnection();

        if (! $this->connection->isConnected()) {
            $connection->disconnect();
            $this->connection = null;

            if ($tries < $this->maxTries) {
                $tries++;
                goto start;
            }

            throw UnableToConnectToSftpHostException::atHostname($this->host);
        }

        return $this->connection = $connection;
    }

    private function setupConnection(): SFTP
    {
        $connection = new SFTP($this->host, $this->port, $this->timeout);

        try {
            $this->authenticate($connection);
        } catch (Throwable $exception) {
            $connection->disconnect();
        }

        return $connection;
    }

    private function authenticate(SFTP $connection): void
    {
        $this->authenticateWithPrivateKey($connection);
    }

    private function authenticateWithPrivateKey(SFTP $connection): void
    {
        $privateKey = $this->loadPrivateKey();

        if ($connection->login($this->username, $privateKey)) {
            return;
        }

        throw UnableToAuthenticateException::withPrivateKey();
    }

    private function loadPrivateKey(): AsymmetricKey
    {
        if (! str_starts_with($this->privateKey, '---') && is_file($this->privateKey)) {
            $this->privateKey = file_get_contents($this->privateKey);
        }

        try {
            return PublicKeyLoader::load($this->privateKey);
        } catch (NoKeyLoadedException $exception) {
            throw new UnableToLoadPrivateKeyException();
        }
    }
}
