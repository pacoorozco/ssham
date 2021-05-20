<?php

namespace App\JsonApi\V1\Hosts;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class HostRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        $data = $this->validationData();

        $unique = Rule::unique('hosts')
            ->where(function ($query) use ($data) {
                return $query->where('username', $data['username']);
            });

        /** @var \App\Models\Host|null $host */
        if ($host = $this->model()) {
            $unique->ignore($host);
        }

        return [
            'hostname' => ['required', 'max:255', $unique],
            'username' => ['required', 'max:255'],
            'port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'authorizedKeysFile' => ['nullable', 'string', 'max:255'],
            'enabled' => ['boolean'],
            'groups' => JsonApiRule::toMany(),
        ];
    }
}
