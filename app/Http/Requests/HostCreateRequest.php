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

use Illuminate\Validation\Rule;

/**
 * Class HostCreateRequest
 *
 * @package App\Http\Requests
 *
 * @property string $hostname
 * @property string $username
 * @property array  $groups
 */
class HostCreateRequest extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $hostname = $this->hostname;
        $username = $this->username;

        return [
            'hostname' => ['required', 'min:5', 'max:255',
                // 'hostname' and 'username' combination must be unique
                Rule::unique('hosts')->where(function ($query) use ($hostname, $username) {
                    return $query->where('hostname', $hostname)
                        ->where('username', $username);
                })],
            'username' => ['required', 'max:255'],
        ];
    }

}
