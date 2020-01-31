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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2020 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Requests;

use App\Rules\ValidRSAPrivateKeyRule;
use App\Rules\ValidRSAPublicKeyRule;

class SettingsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Overrides the parent's getValidatorInstance() to sanitize user input before validation
     *
     * @return mixed
     */
    protected function getValidatorInstance()
    {
        $this->sanitize();
        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'private_key' => ['required', new ValidRSAPrivateKeyRule()],
            'public_key' => ['required', new ValidRSAPublicKeyRule()],
            'ssh_port' => ['required', 'numeric'],
            'ssh_timeout' => ['required', 'numeric', 'min:5', 'max:15'],
        ];
    }

    /**
     * Sanitizes user input. In special 'public_key' to remove carriage returns
     */
    protected function sanitize()
    {
        $input = $this->all();

        // Removes carriage returns from 'public_key' input
        $input['public_key'] = str_replace(["\n", "\t", "\r"], '', $input['public_key']);

        $this->replace($input);
    }
}
