<?php

declare(strict_types=1);

namespace App\Services\Pusher;

use App\Exceptions\PusherException;
use phpseclib3\Net\SFTP;
use Throwable;

class SftpPusher implements PusherAdapter
{
    public function __construct(
        private ConnectionProvider $connectionProvider,
    ) {
    }

    public function write(string $path, string $contents, int $permission): void
    {
        try {
            $this->upload($path, $contents, $permission);
        } catch (PusherException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new PusherException($exception->getMessage());
        }
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    private function upload(string $path, string $contents, int $permission): void
    {
        $connection = $this->connectionProvider->provideConnection();

        if (! $connection->put($path, $contents, SFTP::SOURCE_STRING)) {
            throw new PusherException('Not able to write the file: '.$path);
        }

        $this->setVisibility($path, $permission);
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    private function setVisibility(string $path, int $permission): void
    {
        $connection = $this->connectionProvider->provideConnection();

        if (! $connection->chmod($permission, $path, false)) {
            throw new PusherException('Unable to set permission to the file: '.$path);
        }
    }

    /**
     * @throws \App\Exceptions\PusherException
     */
    private function execute(string $command): int
    {
        $connection = $this->connectionProvider->provideConnection();

        try {
            $connection->enableQuietMode();

            $isExecuted = $connection->exec($command);
        } catch (Throwable $exception) {
            throw new PusherException('Not able to execute command: '.$exception->getMessage());
        } finally {
            $connection->disableQuietMode();
        }

        if (false === $isExecuted) {
            throw new PusherException('Not able to execute command.');
        }

        return (int) $connection->getExitStatus();
    }

    public function exec(string $command): void
    {
        $exitStatus = $this->execute($command);

        if ($exitStatus !== 0) {
            throw new PusherException('Command execution failed, error: '.$exitStatus);
        }
    }
}
