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

use App\Models\Hostgroup;
use Illuminate\Validation\Rule;

class HostUpdateRequest extends Request
{
    public function rules(): array
    {
        return [
            'enabled' => [
                'required',
                'boolean',
            ],
            'port' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
            'authorized_keys_file' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'groups.*' => [
                Rule::forEach(function ($value, $attribute) {
                    return [
                        Rule::exists(Hostgroup::class, 'id'),
                    ];
                }),
            ],
        ];
    }

    public function port(): ?int
    {
        return (int) $this->input('port');
    }

    public function authorized_keys_file(): ?string
    {
        return $this->input('authorized_keys_file');
    }

    public function groups(): array
    {
        return $this->input('groups', []);
    }

    public function enabled(): bool
    {
        return $this->boolean('enabled');
    }
}
