<?php

namespace App\JsonApi\V1\Hostgroups;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class HostgroupRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        $unique = Rule::unique('hostgroups');

        /** @var \App\Models\Hostgroup|null $group */
        if ($group = $this->model()) {
            $unique->ignore($group);
        }

        return [
            'name' => ['required', 'min:5', 'max:255', $unique],
            'description' => ['nullable', 'max:255'],
            'hosts' => JsonApiRule::toMany(),
        ];
    }
}
