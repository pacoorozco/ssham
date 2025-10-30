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

namespace App\Http\Controllers;

use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ControlRuleDataTablesController extends Controller
{
    public function __invoke(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', ControlRule::class);

        $rules = ControlRule::select([
            'id',
            'name',
            'source_id',
            'target_id',
        ]);

        return $dataTable->eloquent($rules)
            ->addColumn('source', function (ControlRule $rule) {
                /** @var Keygroup $source */
                $source = $rule->source;

                return $source->present()->linkableNameWithKeysCount();
            })
            ->addColumn('target', function (ControlRule $rule) {
                /** @var Hostgroup $target */
                $target = $rule->target;

                return $target->present()->linkableNameWithHostsCount();
            })
            ->addColumn('actions', function (ControlRule $rule) {
                return view('rule._table_actions')
                    ->with('rule', $rule)
                    ->render();
            })
            ->rawColumns(['source', 'target', 'actions'])
            ->removeColumn(['id', 'source_id', 'target_id'])
            ->toJson();
    }
}
