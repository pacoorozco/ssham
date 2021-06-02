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

namespace App\Http\Controllers;

use App\Http\Requests\ControlRuleCreateRequest;
use App\Jobs\CreateControlRule;
use App\Jobs\DeleteControlRule;
use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use yajra\Datatables\Datatables;

class ControlRuleController extends Controller
{
    public function index(): View
    {
        return view('rule.index');
    }

    public function create(): View
    {
        // Get all existing user and hosts groups
        $sources = Keygroup::orderBy('name')->get()->mapWithKeys(
            fn($group) => [$group->id => $group->present()->nameWithKeysCount()]
        );
        $targets = Hostgroup::orderBy('name')->get()->mapWithKeys(
            fn($group) => [$group->id => $group->present()->nameWithHostsCount()]
        );

        return view('rule.create')
            ->with('sources', $sources)
            ->with('targets', $targets);
    }

    public function store(ControlRuleCreateRequest $request): RedirectResponse
    {
        $rule = CreateControlRule::dispatchSync(
            $request->name(),
            $request->source(),
            $request->target(),
            $request->action()
        );

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.create.success', ['rule' => $rule->id]));
    }

    public function destroy(ControlRule $rule): RedirectResponse
    {
        DeleteControlRule::dispatchSync($rule);

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.delete.success', ['rule' => $rule->id]));
    }

    public function data(Datatables $datatable): JsonResponse
    {
        $rules = ControlRule::select([
            'id',
            'name',
            'source_id',
            'target_id',
            'action',
        ]);

        return $datatable->eloquent($rules)
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
            ->editColumn('action', function (ControlRule $rule) {
                return $rule->present()->actionWithIcon;
            })
            ->addColumn('actions', function (ControlRule $rule) {
                return view('rule._table_actions')
                    ->with('rule', $rule)
                    ->render();
            })
            ->rawColumns(['source', 'target', 'action', 'actions'])
            ->removeColumn(['source_id', 'target_id'])
            ->toJson();
    }
}
