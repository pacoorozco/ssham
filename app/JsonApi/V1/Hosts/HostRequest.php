<?php

namespace App\JsonApi\V1\Hosts;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class HostRequest extends ResourceRequest
{
    public function rules(): array
    {
        $data = $this->validationData();

        // Scope the query to only search records that have the proper 'username'.
        $unique = Rule::unique('hosts')
            ->where(function ($query) use ($data) {
                return $query->where('username', $data['username']);
            });

        if ($host = $this->model()) {
            $unique = $unique->ignore($host);
        }

        return [
            'hostname' => [
                'required',
                'max:255',
                $unique,
            ],
            'username' => [
                'required',
                'max:255',
            ],
            'port' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
            'authorizedKeysFile' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'enabled' => [
                'boolean',
            ],
            'groups' => JsonApiRule::toMany(),
        ];
    }
}
