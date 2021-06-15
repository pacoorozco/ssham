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
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                new EnumValue(Roles::class),
            ],
            'enabled' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        // checks user current password
        // before making changes
        $validator->after(function ($validator) {
            if (Auth::id() !== $this->user->id) {
                return;
            }
            if ($this->password() && ! Hash::check($this->current_password, $this->user->password)) {
                $validator->errors()->add('current_password', __('user/messages.edit.incorrect_password'));
            }
            if (! $this->enabled()) {
                $validator->errors()->add('enabled', __('user/messages.edit.disabled_status_not_allowed'));
            }
        });
    }

    public function email(): string
    {
        return $this->input('email');
    }

    public function password(): ?string
    {
        return $this->input('password');
    }

    public function enabled(): bool
    {
        return (bool) $this->input('enabled');
    }

    public function role(): Roles
    {
        $roleName = $this->input('role');
        return Roles::fromValue($roleName);
    }
}
