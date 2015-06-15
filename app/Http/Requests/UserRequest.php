<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class UserRequest extends Request
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
            'name'          => 'required|min:3',
            'type'          => 'required',
            'password'      => 'required|confirmed|min:8',
            'active'        => 'required|boolean',
        ];
    }

}
