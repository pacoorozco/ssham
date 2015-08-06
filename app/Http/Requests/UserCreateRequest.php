<?php

namespace SSHAM\Http\Requests;

use SSHAM\Http\Requests\Request;

class UserCreateRequest extends Request
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
     * Overrides the parent's getValidatorInstance() to sanitize user input before validation
     *
     * @return mixed
     */
    protected function getValidatorInstance() {
        $this->sanitize();
        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|max:255|unique:users',
            'public_key' => 'rsa_key:public',
        ];
    }

    /**
     * Sanitizes user input. In special 'public_key' to remove carriage returns
     */
    protected function sanitize()
    {
        $input = $this->all();

        // Removes carriage returns from 'public_key' input
        $input['public_key'] = str_replace(["\n", "\t", "\r"], '', $input['public_key']);

        $this->replace($input);
    }

}
