<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Request;
use SSHAM\Hostgroup;
use SSHAM\Http\Requests;
use SSHAM\Http\Requests\RuleRequest;
use SSHAM\Rule;
use SSHAM\Usergroup;
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
     * @return View
     */
    public function index()
    {
        return view('rule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        // Get all existing user and hosts groups
        $usergroups = Usergroup::pluck('name', 'id')->all();
        $hostgroups = Hostgroup::pluck('name', 'id')->all();

        return view('rule.create', compact('usergroups', 'hostgroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RuleRequest $request
     * @return RedirectResponse
     */
    public function store(RuleRequest $request)
    {
        $rule = new Rule($request->all());
        $rule->save();

        flash()->success(trans('rule/messages.create.success'));

        return redirect()->route('rules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Rule $rule
     * @return RedirectResponse
     */
    public function destroy(Rule $rule)
    {
        $rule->delete();

        flash()->success(trans('rule/messages.delete.success'));

        return redirect()->route('rules.index');
    }

    /**
     * Return all Users in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (!Request::ajax()) {
            abort(403);
        }

        $rules = Rule::select(array(
            'id', 'usergroup_id', 'hostgroup_id', 'action', 'enabled'
        ));

        return $datatable->usingEloquent($rules)
            ->addColumn('usergroup', function (Rule $rule) {
                return Usergroup::findOrFail($rule->usergroup_id)->name;
            })
            ->addColumn('hostgroup', function (Rule $rule) {
                return Hostgroup::findOrFail($rule->hostgroup_id)->name;
            })
            ->editColumn('action', function (Rule $rule) {
                return ($rule->action == 'allow') ? '<span class="btn btn-sm btn-green"><i class="clip-unlocked"></i> Allowed</span>' : '<span class="btn btn-sm btn-bricky"><i class="clip-locked"></i> Denied</span>';
            })
            ->editColumn('enabled', function (Rule $rule) {
                return ($rule->enabled) ? '<span class="label label-sm label-success">' . trans('general.enabled') . '</span>'
                    : '<span class="label label-sm label-danger">' . trans('general.disabled') . '</span>';
            })
            ->addColumn('actions', function (Rule $rule) {
                return view('partials.rules_dd', array(
                    'model' => 'rules',
                    'id'    => $rule->id
                ))->render();
            })
            ->removeColumn('id')
            ->removeColumn('usergroup_id')
            ->removeColumn('hostgroup_id')
            ->make(true);
    }
}
