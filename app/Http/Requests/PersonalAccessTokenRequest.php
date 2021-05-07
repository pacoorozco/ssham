<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalAccessTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', ' max:255', Rule::unique('personal_access_tokens')],
        ];
    }

    public function requestedUser(): User
    {
        return User::findOrFail($this->route('user'));
    }

    public function name(): string
    {
        return $this->input('name');
    }
}
