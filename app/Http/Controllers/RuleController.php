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
     * @return Response
     */
    public function store(RuleRequest $request)
    {
        $rule = new Rule($request->all());
        $rule->save();

        flash()->success(\Lang::get('rule/messages.create.success'));

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
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function toggleStatus(Rule $rule) {
        if ($rule->active) {
            $rule->setActive(0);
        } else {
            $rule->setActive(1);
        }
    }

    /**
     * Return all Users in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatable)
    {
        $rules = Rule::select(array(
            'id', 'usergroup_id', 'hostgroup_id', 'permission', 'active'
        ));

        return $datatable->usingEloquent($rules)
            ->addColumn('usergroup', function ($model) {
                return Usergroup::findOrFail($model->usergroup_id)->name;
            })
            ->addColumn('hostgroup', function ($model) {
                return Hostgroup::findOrFail($model->hostgroup_id)->name;
            })
            ->addColumn('actions', function ($model) {
                return view('partials.rules_dd', array(
                    'model' => 'users',
                    'id' => $model->id
                ))->render();
            })
            ->removeColumn('id')
            ->removeColumn('usergroup_id')
            ->removeColumn('hostgroup_id')
            ->make(true);
    }
}