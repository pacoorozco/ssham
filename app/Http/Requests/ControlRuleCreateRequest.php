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
 * Class RuleCreateRequest.
 *
 *
 * @property int    $source
 * @property int    $target
 * @property string $name
 * @property string $action
 */
class ControlRuleCreateRequest extends Request
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
        $source = $this->source;
        $target = $this->target;

        return [
            'source' => ['required', 'exists:App\Models\Keygroup,id',
                // 'keygroup' and 'hostgroup' combination must be unique
                Rule::unique('hostgroup_keygroup_permissions', 'source_id')->where(function ($query) use ($source, $target) {
                    return $query->where('source_id', $source)
                        ->where('target_id', $target);
                }), ],
            'target' => ['required', 'exists:App\Models\Hostgroup,id'],
            'action' => ['required', Rule::in(['allow', 'deny'])],
            'name' => ['required', 'string'],
        ];
    }
}
