<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2022 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2022 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Actions;

use App\Enums\Roles;
use App\Models\User;
use App\Rules\UsernameRule;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser
{
    public function __invoke(array $data): User
    {
        $data = Validator::validate($data, $this->rules());

        /* @var User $user */
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return $user->assignRole($data['role'] ?? Roles::Operator);
    }

    private function rules(): array
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
                new EnumValue(Roles::class),
            ],
        ];
    }
}
