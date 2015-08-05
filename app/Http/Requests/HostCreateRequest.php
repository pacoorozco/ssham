<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class HostCreateRequest extends Request
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
            'hostname'  => 'required|min:5|max:255|unique_with:hosts,username',
            'username'  => 'required|max:255',
            'enabled'   => 'required|boolean',
        ];
    }

}
