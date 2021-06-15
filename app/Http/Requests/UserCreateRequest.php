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

use App\Enums\Roles;
use App\Rules\UsernameRule;
use BenSampo\Enum\Rules\EnumValue;

class UserCreateRequest extends Request
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
                'unique:users',
            ],
            'email' => [
                'required',
                'email:rfc',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'role' => [
                'required',
                new EnumValue(Roles::class),
            ],
        ];
    }

    public function username(): string
    {
        return $this->input('username');
    }

    public function email(): string
    {
        return $this->input('email');
    }

    public function password(): string
    {
        return $this->input('password');
    }

    public function role(): Roles
    {
        $roleName = $this->input('role');
        return Roles::fromValue($roleName);
    }
}
