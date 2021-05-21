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
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Database\Factories;

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ControlRuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ControlRule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'source_id' => function () {
                $keygroup = Keygroup::factory()->create();

                return $keygroup->id;
            },
            'target_id' => function () {
                $hostgroup = Hostgroup::factory()->create();

                return $hostgroup->id;
            },
            'action' => $this->faker->randomElement(ControlRuleAction::getValues()),
            'name' => $this->faker->sentence,
        ];
    }
}
