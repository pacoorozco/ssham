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
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Requests;

use App\Rules\ValidRSAPrivateKeyRule;
use App\Rules\ValidRSAPublicKeyRule;

class SettingsRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Overrides the parent's getValidatorInstance() to sanitize user input before validation.
     *
     * @return mixed
     */
    protected function getValidatorInstance()
    {
        $this->sanitize();

        return parent::getValidatorInstance();
    }

    public function rules(): array
    {
        return [
            'authorized_keys' => [
                'required',
                'string',
            ],
            'private_key' => [
                'required',
                new ValidRSAPrivateKeyRule(),
            ],
            'public_key' => [
                'required',
                new ValidRSAPublicKeyRule(),
            ],
            'temp_dir' => [
                'required',
                'string',
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

    /**
     * Sanitizes user input. In special 'public_key' to remove carriage returns.
     */
    protected function sanitize(): void
    {
        $input = $this->all();

        // Removes carriage returns from 'public_key' input
        $input['public_key'] = str_replace(["\n", "\t", "\r"], '', $input['public_key']);

        $this->replace($input);
    }

    public function authorizedKeys(): string
    {
        return $this->input('authorized_keys');
    }

    public function privateKey(): string
    {
        return $this->input('private_key');
    }

    public function publicKey(): string
    {
        return $this->input('public_key');
    }

    public function tempDir(): string
    {
        return $this->input('temp_dir');
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
        return (bool) $this->input('mixed_mode');
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
