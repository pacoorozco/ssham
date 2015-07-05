<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Host;
use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\HostgroupCreateRequest;
use SSHAM\Http\Requests\HostgroupUpdateRequest;
use SSHAM\Hostgroup;
use yajra\Datatables\Datatables;

class HostgroupController extends Controller
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
        return view('hostgroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all existing hosts
        $hosts = Host::lists('hostname', 'id')->all();
        return view('hostgroup.create', compact('hosts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HostgroupCreateRequest $request
     * @return Response
     */
    public function store(HostgroupCreateRequest $request)
    {
        $hostgroup = new Hostgroup($request->all());
        $hostgroup->save();

        // Associate Host's Groups
        if ($request->hosts) {
            $hostgroup->hosts()->sync($request->hosts);
            $hostgroup->save();
        }

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
     * @param  Hostgroup $hostgroup
     * @return Response
     */
    public function edit(Hostgroup $hostgroup)
    {
        // Get all existing hosts
        $hosts = Host::lists('hostname', 'id')->all();
        return view('hostgroup.edit', compact('hostgroup', 'hosts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Hostgroup $hostgroup
     * @param HostgroupUpdateRequest $request
     * @return Response
     */
    public function update(Hostgroup $hostgroup, HostgroupUpdateRequest $request)
    {
        $hostgroup->update($request->all());

        // Associate User's Groups
        if ($request->hosts) {
            $hostgroup->hosts()->sync($request->hosts);
        } else {
            $hostgroup->hosts()->detach();
        }
        $hostgroup->save();

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

    /**
     * Return all Hostgroups in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatable)
    {
        $hostgroups = Hostgroup::select(array(
            'id', 'name', 'description'
        ))->orderBy('name', 'ASC');

        return $datatable->usingEloquent($hostgroups)
            ->addColumn('hosts', function ($model) {
                return count($model->hosts->lists('id')->all());;
            })
            ->addColumn('actions', function ($model) {
                return view('partials.actions_dd', array(
                    'model' => 'hostgroups',
                    'id' => $model->id
                ))->render();
            })
            ->removeColumn('id')
            ->make(true);
    }

}
