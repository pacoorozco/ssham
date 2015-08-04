<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\HostCreateRequest;
use SSHAM\Http\Requests\HostUpdateRequest;
use SSHAM\Host;
use SSHAM\Hostgroup;
use SSHAM\Rule;
use SSHAM\Usergroup;
use yajra\Datatables\Datatables;

class HostController extends Controller
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
        return view('host.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all existing user groups
        $groups = Hostgroup::lists('name', 'id')->all();
        return view('host.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HostCreateRequest $request
     * @return Response
     */
    public function store(HostCreateRequest $request)
    {
        $host = new Host($request->all());
        $host->save();

        // Associate Host's Groups
        if ($request->groups) {
            $host->groups()->sync($request->groups);
            $host->save();
        }

        flash()->success(\Lang::get('host/messages.create.success'));
        
        return redirect()->route('hosts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Host $host
     * @return Response
     */
    public function show(Host $host)
    {
        return view('host.show', compact('host'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Host  $host
     * @return Response
     */
    public function edit(Host $host)
    {
        // Get all existing user groups
        $groups = Hostgroup::lists('name', 'id')->all();
        return view('host.edit', compact('host', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Host $host
     * @param HostUpdateRequest $request
     * @return Response
     */
    public function update(Host $host, HostUpdateRequest $request)
    {
        $host->update($request->all());

        // Associate Host's Groups
        if ($request->groups) {
            $host->groups()->sync($request->groups);
        } else {
            $host->groups()->detach();
        }
        $host->save();

        flash()->success(\Lang::get('host/messages.edit.success'));

        return redirect()->route('hosts.edit', [$host->id]);
    }

    /**
     * Remove host.
     *
     * @param Host $host
     * @return Response
     */
    public function delete(Host $host)
    {
        return view('host.delete', compact('host'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Host $host
     * @return Response
     */
    public function destroy(Host $host)
    {
        $host->delete();

        flash()->success(\Lang::get('host/messages.delete.success'));

        return redirect()->route('hosts.index');
    }

    public function getSSHKeysForHost(Host $host)
    {
        // obtain hostsgroups where host belongs to
        $hostgroups = $host->groups()->get();

        // for each hostgroups obtain usergroups with allow / deny policy
        foreach ($hostgroups as $group) {
            $rules[] = Rule::where('hostgroup_id', $group->id)->get();
        }

        // for each usergroup obtain users, maintain allow / deny policy
        foreach($rules as $rule) {
            dd($rule);
            $usergroups = Usergroup::find($rule->usergroup_id)->get();
        }
        dd($usergroups);

        // construct list with only users with allow policy

        // obtain SSH keys

    }

    /**
     * Return all Hosts in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatable)
    {
        $hosts = Host::select(array(
                'id', 'hostname', 'username', 'type', 'enabled'
        ))->orderBy('hostname', 'ASC');

        return $datatable->usingEloquent($hosts)
            ->editColumn('enabled', function($model) {
                return ($model->enabled) ? '<span class="label label-sm label-success">' . \Lang::get('general.enabled') . '</span>'
                    : '<span class="label label-sm label-danger">' . \Lang::get('general.disabled') . '</span>';
            })
            ->addColumn('groups', function ($model) {
                return count($model->groups->lists('id')->all());
            })
            ->addColumn('actions', function($model) {
                return view('partials.actions_dd', array(
                    'model' => 'hosts',
                    'id' => $model->id
                ))->render();
            })
            ->removeColumn('id')
            ->make(true);
    }

}
