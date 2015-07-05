<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class UsergroupCreateRequest extends Request
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
            'name'  => 'required|min:5|max:255|unique:usergroups',
            'description'  => 'max:255',
        ];
    }

}
