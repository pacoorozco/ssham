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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserUpdateRequest.
 *
 *
 * @property \App\User $user
 * @property string    $email
 * @property string    $password
 * @property string    $current_password
 * @property bool   $enabled
 */
class UserUpdateRequest extends Request
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
        $user = $this->user;

        return [
            'email' => ['required', 'email:rfc', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'enabled' => ['required', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        // checks user current password
        // before making changes
        $validator->after(function ($validator) {
            if (Auth::id() !== $this->user->id) {
                return;
            }
            if ($this->filled('password') && ! Hash::check($this->current_password, $this->user->password)) {
                $validator->errors()->add('current_password', __('user/messages.edit.incorrect_password'));
            }
            if (! $this->enabled) {
                $validator->errors()->add('enabled', __('user/messages.edit.disabled_status_not_allowed'));
            }
        });
    }
}
