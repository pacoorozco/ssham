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
use App\Models\Activity;
use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use yajra\Datatables\Datatables;

class ControlRuleController extends Controller
{
    /**
     * Create a new controller instance.
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
        $sources = Keygroup::orderBy('name')->pluck('name', 'id');
        $targets = Hostgroup::orderBy('name')->pluck('name', 'id');

        return view('rule.create', compact('sources', 'targets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ControlRuleCreateRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ControlRuleCreateRequest $request)
    {
        try {
            $rule = ControlRule::create([
                'name' => $request->name,
                'source_id' => $request->source,
                'target_id' => $request->target,
                'action' => $request->action,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('rule/messages.create.error'));
        }

        activity()
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create rule '%s'.", $rule->name));

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.create.success', ['rule' => $rule->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ControlRule  $rule
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(ControlRule $rule)
    {
        $id = $rule->id;

        try {
            $rule->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(__('rule/messages.delete.error'));
        }

        activity()
            ->performedOn($rule)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Delete rule '%s'.", $rule->name));

        return redirect()->route('rules.index')
            ->withSuccess(__('rule/messages.delete.success', ['rule' => $id]));
    }

    /**
     * Return all Users in order to be used as Datatables.
     *
     * @param  Datatables  $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $rules = ControlRule::select([
            'id',
            'name',
            'source_id',
            'target_id',
            'action',
        ])
            ->orderBy('id', 'asc');

        return $datatable->eloquent($rules)
            ->addColumn('source', function (ControlRule $rule) {
                return $rule->source;
            })
            ->addColumn('target', function (ControlRule $rule) {
                return $rule->target;
            })
            ->editColumn('action', function (ControlRule $rule) {
                return ($rule->action == 'allow') ? '<i class="fa fa-lock-open"></i> ' . /** @scrutinizer ignore-type */ __('rule/table.allowed')
                    : '<i class="fa fa-lock"></i> ' . /** @scrutinizer ignore-type */ __('rule/table.denied');
            })
            ->addColumn('actions', function (ControlRule $rule) {
                return view('rule._table_actions')
                    ->with('rule', $rule)
                    ->render();
            })
            ->rawColumns(['action', 'actions'])
            ->removeColumn(['source_id', 'target_id'])
            ->toJson();
    }
}
