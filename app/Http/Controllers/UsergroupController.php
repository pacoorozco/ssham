<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\GroupRequest;
use SSHAM\Usergroup;
use yajra\Datatables\Datatables;

class UsergroupController extends Controller
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
        return view('usergroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('usergroup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupRequest $request
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $usergroup = new Usergroup($request->all());
        $usergroup->save();

        flash()->success(\Lang::get('usergroup/messages.create.success'));
        
        return redirect()->route('usergroups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Usergroup $usergroup
     * @return Response
     */
    public function show(Usergroup $usergroup)
    {
        return view('usergroup.show', compact('usergroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Usergroup  $usergroup
     * @return Response
     */
    public function edit(Usergroup $usergroup)
    {
        return view('usergroup.edit', compact('usergroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Usergroup $usergroup
     * @param GroupRequest $request
     * @return Response
     */
    public function update(Usergroup $usergroup, GroupRequest $request)
    {
        $usergroup->update($request->all());

        flash()->success(\Lang::get('usergroup/messages.edit.success'));

        return redirect()->route('usergroups.edit', [$usergroup->id]);
    }

    /**
     * Remove usergroup.
     *
     * @param Usergroup $usergroup
     * @return Response
     */
    public function delete(Usergroup $usergroup)
    {
        return view('usergroup.delete', compact('usergroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Usergroup $usergroup
     * @return Response
     */
    public function destroy(Usergroup $usergroup)
    {
        $usergroup->delete();

        flash()->success(\Lang::get('usergroup/messages.delete.success'));

        return redirect()->route('usergroups.index');
    }

    public function data(Datatables $datatable)
    {
        $usergroups = Usergroup::select(array(
                'id', 'name', 'description'
        ));

        return $datatable->usingEloquent($usergroups)
                ->addColumn('actions', function($model) {
                    return view('partials.actions_dd', array(
                            'model' => 'usergroups',
                            'id' => $model->id
                        ))->render();
                })
                ->removeColumn('id')
                ->make(true);
    }

}
