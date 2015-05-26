<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class UserForm extends Request
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
            'name'          => 'required',
            'email'         => 'required',
            'type'          => 'required',
            'password'      => '',
            'publicKey'     => 'required',
            'fingerprint'   => 'required',
            'active'        => 'required',
        ];
    }

}
