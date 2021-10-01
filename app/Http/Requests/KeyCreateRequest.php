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

use App\Enums\KeyOperation;
use App\Rules\UsernameRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use PacoOrozco\OpenSSH\Rules\PublicKeyRule;

class KeyCreateRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'max:255',
                new UsernameRule(),
                'unique:keys',
            ],
            'operation' => [
                'required',
                Rule::in([
                    KeyOperation::CREATE_OPERATION,
                    KeyOperation::IMPORT_OPERATION,
                ]),
            ],
            'public_key' => [
                new RequiredIf($this->wantsImportKey()),
                new PublicKeyRule(),
            ],
        ];
    }

    public function wantsImportKey(): bool
    {
        return $this->input('operation') === KeyOperation::IMPORT_OPERATION;
    }

    public function username(): string
    {
        return $this->input('username');
    }

    public function publicKey(): string
    {
        return $this->input('public_key', '');
    }

    public function groups(): ?array
    {
        return $this->input('groups');
    }

    public function wantsCreateKey(): bool
    {
        return $this->input('operation') === KeyOperation::CREATE_OPERATION;
    }

    protected function getValidatorInstance(): Validator
    {
        //$this->sanitize();

        return parent::getValidatorInstance();
    }

    /**
     * Sanitizes user input. In special 'public_key_input' to remove carriage returns.
     */
    protected function sanitize(): void
    {
        $input = $this->all();

        // Removes carriage returns from 'public_key' input
        if (isset($input['public_key'])) {
            $input['public_key'] = str_replace(["\n", "\t", "\r"], '', $input['public_key']);
        }

        $this->replace($input);
    }
}
