<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\GroupRequest;
use SSHAM\Hostgroup;
use yajra\Datatables\Datatables;

class HostgroupController extends Controller
{

    /**
     * Create a new controller instance.
     * 
     * @return void
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
        return view('hostgroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('hostgroup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupRequest $request
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $hostgroup = new Hostgroup($request->all());
        $hostgroup->save();

        flash()->success(\Lang::get('hostgroup/messages.create.success'));
        
        return redirect()->route('hostgroups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Hostgroup $hostgroup
     * @return Response
     */
    public function show(Hostgroup $hostgroup)
    {
        return view('hostgroup.show', compact('hostgroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Hostgroup  $hostgroup
     * @return Response
     */
    public function edit(Hostgroup $hostgroup)
    {
        return view('hostgroup.edit', compact('hostgroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Hostgroup $hostgroup
     * @param GroupRequest $request
     * @return Response
     */
    public function update(Hostgroup $hostgroup, GroupRequest $request)
    {
        $hostgroup->update($request->all());

        flash()->success(\Lang::get('hostgroup/messages.edit.success'));

        return redirect()->route('hostgroups.edit', [$hostgroup->id]);
    }

    /**
     * Remove hostgroup.
     *
     * @param Hostgroup $hostgroup
     * @return Response
     */
    public function delete(Hostgroup $hostgroup)
    {
        return view('hostgroup.delete', compact('hostgroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Hostgroup $hostgroup
     * @return Response
     */
    public function destroy(Hostgroup $hostgroup)
    {
        $hostgroup->delete();

        flash()->success(\Lang::get('hostgroup/messages.delete.success'));

        return redirect()->route('hostgroups.index');
    }

    public function data(Datatables $datatable)
    {
        $hostgroups = Hostgroup::select(array(
                'id', 'name', 'description'
        ));

        return $datatable->usingEloquent($hostgroups)
                ->addColumn('actions', function($model) {
                    return view('partials.actions_dd', array(
                            'model' => 'hostgroups',
                            'id' => $model->id
                        ))->render();
                })
                ->removeColumn('id')
                ->make(true);
    }

}
