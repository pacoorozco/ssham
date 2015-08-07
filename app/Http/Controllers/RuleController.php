<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests;
use SSHAM\Rule;
use SSHAM\Usergroup;
use SSHAM\Hostgroup;
use yajra\Datatables\Datatables;
use SSHAM\Http\Requests\RuleRequest;

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
     * @return Response
     */
    public function index()
    {
        return view('rule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all existing user and hosts groups
        $usergroups = Usergroup::lists('name', 'id')->all();
        $hostgroups = Hostgroup::lists('name', 'id')->all();
        return view('rule.create', compact('usergroups', 'hostgroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RuleRequest $request
     * @return Response
     */
    public function store(RuleRequest $request)
    {
        $rule = new Rule($request->all());
        $rule->save();

        flash()->success(trans('rule/messages.create.success'));

        return redirect()->route('rules.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Rule $rule
     * @return Response
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (! \Request::ajax()) {
            \App::abort(403);
        }

        $rules = Rule::select(array(
            'id', 'usergroup_id', 'hostgroup_id', 'action', 'enabled'
        ));

        return $datatable->usingEloquent($rules)
            ->addColumn('usergroup', function ($model) {
                return Usergroup::findOrFail($model->usergroup_id)->name;
            })
            ->addColumn('hostgroup', function ($model) {
                return Hostgroup::findOrFail($model->hostgroup_id)->name;
            })
            ->editColumn('action', function($model) {
                return ($model->action == 'allow') ? '<span class="btn btn-sm btn-green"><i class="clip-unlocked"></i> Allowed</span>' : '<span class="btn btn-sm btn-bricky"><i class="clip-locked"></i> Denied</span>';
            })
            ->editColumn('enabled', function($model) {
                return ($model->enabled) ? '<span class="label label-sm label-success">' . trans('general.enabled') . '</span>'
                    : '<span class="label label-sm label-danger">' . trans('general.disabled') . '</span>';
            })
            ->addColumn('actions', function ($model) {
                return view('partials.rules_dd', array(
                    'model' => 'rules',
                    'id' => $model->id
                ))->render();
            })
            ->removeColumn('id')
            ->removeColumn('usergroup_id')
            ->removeColumn('hostgroup_id')
            ->make(true);
    }
}
