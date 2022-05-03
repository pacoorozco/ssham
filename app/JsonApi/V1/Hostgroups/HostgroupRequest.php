<?php

namespace App\JsonApi\V1\Hostgroups;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class HostgroupRequest extends ResourceRequest
{
    public function rules(): array
    {
        $uniqueName = Rule::unique('hostgroups');

        if ($group = $this->model()) {
            $uniqueName = $uniqueName->ignoreModel($group);
        }

        return [
            'name' => [
                'required',
                'min:5',
                'max:255',
                $uniqueName,
            ],
            'description' => [
                'string',
                'nullable',
            ],
            'hosts' => JsonApiRule::toMany(),
        ];
    }
}
