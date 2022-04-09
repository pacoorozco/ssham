<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Requests;

use App\Models\Host;
use App\Models\Hostgroup;
use Illuminate\Validation\Rule;

class HostgroupCreateRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:5',
                'max:255',
                Rule::unique(Hostgroup::class),
            ],
            'description' => [
                'string',
                'nullable',
            ],
            'hosts.*' => [
                Rule::forEach(function ($value, $attribute) {
                    return [
                        Rule::exists(Host::class, 'id')
                    ];
                }),
            ],
        ];
    }

    public function name(): string
    {
        return $this->input('name');
    }

    public function description(): ?string
    {
        return $this->input('description');
    }

    public function hosts(): ?array
    {
        return $this->input('hosts');
    }
}
