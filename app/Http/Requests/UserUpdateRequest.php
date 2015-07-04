<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

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
        $user = $this->route('users');

        return [
            'username'  => 'sometimes|min:5|max:255|unique:users,username,' . $user->id,
            'enabled'   => 'required|boolean',
        ];
    }

}
