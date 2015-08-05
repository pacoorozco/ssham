<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class HostUpdateRequest extends Request
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
        $host = $this->route('hosts');

        return [
            'hostname'  => 'sometimes|min:5|max:255|unique_with:hosts,username,' . $host->id,
            'username'  => 'sometimes|max:255',
            'enabled'   => 'required|boolean',
        ];
    }

}
