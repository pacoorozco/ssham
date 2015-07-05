<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class RuleRequest extends Request
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
        return [
            'usergroup_id' => 'required|unique:usergroup_hostgroup_permissions',
            'hostgroup_id' => 'required',
            'action' => 'required',
            'name' => 'max:255',
            'enabled' => 'required|boolean'
        ];
    }

}
