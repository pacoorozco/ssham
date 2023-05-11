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

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rule;

class ControlRuleCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'source' => [
                'required',
                Rule::exists(Keygroup::class, 'id'),
                Rule::unique(ControlRule::class, 'source_id')
                    ->where(fn ($query) => $query->where('target_id', $this->input('target'))),
            ],
            'target' => [
                'required',
                Rule::exists(Hostgroup::class, 'id'),
            ],
            'action' => [
                'required',
                new EnumValue(ControlRuleAction::class),
            ],
            'name' => [
                'required',
                'string',
            ],
        ];
    }

    public function source(): int
    {
        return $this->input('source');
    }

    public function target(): int
    {
        return $this->input('target');
    }

    public function action(): ControlRuleAction
    {
        return ControlRuleAction::fromValue($this->input('action'));
    }

    public function name(): string
    {
        return $this->input('name');
    }
}
