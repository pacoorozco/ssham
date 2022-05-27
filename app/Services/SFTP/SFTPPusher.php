<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Services\SFTP;

use App\Exceptions\PusherException;
use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Net\SFTP;
use Throwable;

class SFTPPusher
{
    protected SFTP $sftp;

    public function __construct(
        string $hostname,
        int $port = 22,
        int $timeout = 10
    ) {
        $this->sftp = new SFTP($hostname, $port, $timeout);
    }

    /**
     * Logs in the server using the provided credentials.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function login(string $username, AsymmetricKey $key): void
    {
        try {
            $isLogged = $this->sftp->login($username, $key);
        } catch (Throwable $exception) {
            throw new PusherException($exception->getMessage());
        }

        if (false === $isLogged) {
            throw new PusherException('Invalid credentials');
        }
    }

    /**
     * Pushes the supplied data to a file in the server, remote permission will be set if it's specified.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function pushDataTo(string $data, string $remotePath, int $permission = 0700): void
    {
        if (false === $this->sftp->put($remotePath, $data, SFTP::SOURCE_STRING)) {
            throw new PusherException('Unable to create the file: ' . $remotePath);
        }

        if (false === $this->sftp->chmod($permission, $remotePath)) {
            throw new PusherException('Unable to set permission to the file: ' . $remotePath);
        }
    }

    /**
     * Executes a command in the remote server.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function exec(string $command): void
    {
        try {
            $this->sftp->enableQuietMode();

            $isExecuted = $this->sftp->exec($command);
        } catch (Throwable $exception) {
            throw new PusherException('Unable to execute command: ' . $exception->getMessage());
        } finally {
            $this->sftp->disableQuietMode();
        }

        if (false === $isExecuted) {
            throw new PusherException('Unable to execute command');
        }

        if ($this->sftp->getExitStatus() !== 0) {
            throw new PusherException('Command execution failed, error: ' . $this->sftp->getExitStatus());
        }
    }

    /**
     * Disconnects from the server.
     */
    public function disconnect(): void
    {
        $this->sftp->disconnect();
    }
}
