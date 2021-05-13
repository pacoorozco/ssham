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

use App\Rules\UsernameRule;
use App\Rules\ValidRSAPublicKeyRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class KeyCreateRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getValidatorInstance(): Validator
    {
        $this->sanitize();

        return parent::getValidatorInstance();
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'max:255', new UsernameRule(), 'unique:keys'],
            'public_key' => ['required', Rule::in(['create', 'import'])],
            'public_key_input' => ['required_if:public_key,import', new ValidRSAPublicKeyRule()],
        ];
    }

    /**
     * Sanitizes user input. In special 'public_key_input' to remove carriage returns.
     */
    protected function sanitize(): void
    {
        $input = $this->all();

        // Removes carriage returns from 'public_key' input
        if (isset($input['public_key_input'])) {
            $input['public_key_input'] = str_replace(["\n", "\t", "\r"], '', $input['public_key_input']);
        }

        $this->replace($input);
    }
}
