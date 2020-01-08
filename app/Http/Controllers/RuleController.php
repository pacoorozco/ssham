<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;


use App\Hostgroup;
use App\Http\Requests\RuleCreateRequest;
use App\Http\Requests\RuleUpdateRequest;
use App\Rule;
use App\Usergroup;
use yajra\Datatables\Datatables;

class RuleController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('rule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing user and hosts groups
        $usergroups = Usergroup::orderBy('name')->pluck('name', 'id');
        $hostgroups = Hostgroup::orderBy('name')->pluck('name', 'id');

        return view('rule.create', compact('usergroups', 'hostgroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RuleCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RuleCreateRequest $request)
    {
        Rule::create([
            'name' => $request->name,
            'usergroup_id' => $request->usergroup,
            'hostgroup_id' => $request->hostgroup,
            'action' => $request->action,
        ]);

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.create.success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Rule              $rule
     * @param RuleUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Rule $rule, RuleUpdateRequest $request)
    {
        $rule->update([
            'enabled' => $request->enabled,
        ]);

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.edit.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rule $rule
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Rule $rule)
    {
        $rule->delete();

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.delete.success'));
    }

    /**
     * Return all Users in order to be used as Datatables
     *
     * @param Datatables $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {

        $rules = Rule::select([
            'id',
            'name',
            'usergroup_id',
            'hostgroup_id',
            'action',
            'enabled',
        ])
            ->orderBy('id', 'asc');

        return $datatable->eloquent($rules)
            ->addColumn('usergroup', function (Rule $rule) {
                return Usergroup::findOrFail($rule->usergroup_id)->name;
            })
            ->addColumn('hostgroup', function (Rule $rule) {
                return Hostgroup::findOrFail($rule->hostgroup_id)->name;
            })
            ->editColumn('action', function (Rule $rule) {
                return ($rule->action == 'allow') ? '<i class="fa fa-lock-open"></i> ' . /** @scrutinizer ignore-type */ __('rule/table.allowed')
                    : '<i class="fa fa-lock"></i> ' . /** @scrutinizer ignore-type */ __('rule/table.denied');
            })
            ->addColumn('actions', function (Rule $rule) {
                return view('rule._table_actions')
                    ->with('rule', $rule)
                    ->render();
            })
            ->rawColumns(['action', 'enabled', 'actions'])
            ->removeColumn(['usergroup_id', 'hostgroup_id', 'enabled'])
            ->toJson();
    }
}
