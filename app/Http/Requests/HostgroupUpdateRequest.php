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
 * Class HostgroupUpdateRequest.
 *
 *
 * @property \App\Hostgroup $hostgroup
 * @property string         $name
 * @property string         $description
 * @property array          $hosts
 */
class HostgroupUpdateRequest extends Request
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
        $group = $this->hostgroup;

        return [
            'name' => ['required', 'min:5', 'max:255', Rule::unique('keygroups')->ignore($group->id)],
            'description' => ['max:255'],
        ];
    }
}
