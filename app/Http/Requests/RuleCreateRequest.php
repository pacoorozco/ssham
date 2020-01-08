<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RuleCreateRequest extends Request
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
        $usergroup = $this->usergroup;
        $hostgroup = $this->hostgroup;

        return [
            'usergroup' => ['required', 'exists:App\Usergroup,id',
                // 'usergroup' and 'hostgroup' combination must be unique
                Rule::unique('hostgroup_usergroup_permissions', 'usergroup_id')->where(function ($query) use ($usergroup, $hostgroup) {
                    return $query->where('usergroup_id', $usergroup)
                        ->where('hostgroup_id', $hostgroup);
                })],
            'hostgroup' => ['required', 'exists:App\Hostgroup,id'],
            'action' => ['required', Rule::in(['allow', 'deny'])],
            'name' => ['nullable', 'string'],
        ];
    }

}
