<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

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
        $group = $this->route('hostgroups');

        return [
            'name'  => 'required|min:5|max:255|unique:hostgroups,name,' . $group->id,
            'description'  => 'max:255',
        ];
    }

}
