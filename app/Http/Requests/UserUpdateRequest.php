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

use App\Enums\Roles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user;

        return [
            'email' => [
                'required',
                'email:rfc',
                'unique:users,email,'.$user->id,
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
            'role' => [
                'required',
                Rule::enum(Roles::class),
            ],
            'enabled' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if (Auth::id() !== $this->user->id) {
                    return;
                }
                // checks user current password
                if ($this->password() && ! Hash::check($this->current_password, $this->user->password)) {
                    $validator->errors()->add('current_password', __('user/messages.edit.incorrect_password'));
                }
                if (! $this->enabled()) {
                    $validator->errors()->add('enabled', __('user/messages.edit.disabled_status_not_allowed'));
                }
                if ($this->role() !== $this->user->role) {
                    $validator->errors()->add('role', __('user/messages.edit.role_change_not_allowed'));
                }
            },
        ];
    }

    public function password(): string
    {
        return $this->string('password');
    }

    public function enabled(): bool
    {
        return $this->boolean('enabled');
    }

    public function role(): Roles
    {
        $roleName = $this->input('role');

        return Roles::from($roleName);
    }

    public function email(): string
    {
        return $this->string('email');
    }
}
