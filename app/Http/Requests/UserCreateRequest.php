<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class UserCreateRequest extends Request
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
    protected function getValidatorInstance() {
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
            'username' => 'required|max:255|unique:users',
            'create_rsa_key' => 'required|boolean',
            'public_key' => 'required_if:create_rsa_key,0|rsa_key:public',
        ];
    }

    /**
     * Sanitizes user input. In special 'public_key' to remove carriage returns
     */
    protected function sanitize()
    {
        $input = $this->all();

        // Removes carriage returns from 'public_key' input
        if (isset($input['public_key'])) {
            $input['public_key'] = str_replace(["\n", "\t", "\r"], '', $input['public_key']);
        }

        $this->replace($input);
    }

}
