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

use App\Actions\CreateRuleAction;
use App\Http\Requests\ControlRuleCreateRequest;
use App\Jobs\DeleteControlRule;
use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ControlRuleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ControlRule::class, 'rule');
    }

    public function index(): View
    {
        return view('rule.index');
    }

    public function create(): View
    {
        // Get all existing user and hosts groups
        $sources = Keygroup::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(
                fn ($group) => [$group->id => $group->present()->nameWithKeysCount()]
            );

        $targets = Hostgroup::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(
                fn ($group) => [$group->id => $group->present()->nameWithHostsCount()]
            );

        return view('rule.create')
            ->with('sources', $sources)
            ->with('targets', $targets);
    }

    public function store(ControlRuleCreateRequest $request, CreateRuleAction $createRule): RedirectResponse
    {
        $rule = $createRule(
            name: $request->name(),
            source: $request->source(),
            target: $request->target(),
            action: $request->action()
        );

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.create.success', ['rule' => $rule->id]));
    }

    public function destroy(ControlRule $rule): RedirectResponse
    {
        $ruleId = $rule->id;

        $rule->delete();

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.delete.success', ['rule' => $ruleId]));
    }
}
