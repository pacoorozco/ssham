<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Requests;

use PacoOrozco\OpenSSH\Rules\PrivateKeyRule;

class SettingsRequest extends Request
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'authorized_keys' => [
                'required',
                'string',
            ],
            'private_key' => [
                'required',
                new PrivateKeyRule(),
            ],
            'ssh_port' => [
                'required',
                'numeric',
            ],
            'ssh_timeout' => [
                'required',
                'numeric',
                'min:5',
                'max:15',
            ],
            'mixed_mode' => [
                'required',
                'boolean',
            ],
            'ssham_file' => [
                'required',
                'string',
            ],
            'non_ssham_file' => [
                'required',
                'string',
            ],
            'cmd_remote_updater' => [
                'required',
                'string',
            ],
        ];
    }

    public function authorizedKeys(): string
    {
        return $this->input('authorized_keys');
    }

    public function privateKey(): string
    {
        return $this->input('private_key');
    }

    public function sshTimeout(): int
    {
        return (int) $this->input('ssh_timeout');
    }

    public function sshPort(): int
    {
        return (int) $this->input('ssh_port');
    }

    public function mixedMode(): bool
    {
        return $this->boolean('mixed_mode');
    }

    public function sshamFile(): string
    {
        return $this->input('ssham_file');
    }

    public function nonSSHAMFile(): string
    {
        return $this->input('non_ssham_file');
    }

    public function cmdRemoteUpdater(): string
    {
        return $this->input('cmd_remote_updater');
    }
}
