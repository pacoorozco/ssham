<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\UsergroupCreateRequest;
use SSHAM\Http\Requests\UsergroupUpdateRequest;
use SSHAM\Usergroup;
use SSHAM\User;
use yajra\Datatables\Datatables;

class UsergroupController extends Controller
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
        return view('usergroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all existing users
        $users = User::lists('username', 'id')->all();
        return view('usergroup.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UsergroupCreateRequest $request
     * @return Response
     */
    public function store(UsergroupCreateRequest $request)
    {
        $usergroup = new Usergroup($request->all());
        $usergroup->save();

        // Associate Users to User's group
        if ($request->users) {
            $usergroup->users()->sync($request->users);
            $usergroup->save();
        }

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
        // Get all existing users
        $users = User::lists('username', 'id')->all();
        return view('usergroup.edit', compact('usergroup', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Usergroup $usergroup
     * @param UsergroupUpdateRequest $request
     * @return Response
     */
    public function update(Usergroup $usergroup, UsergroupUpdateRequest $request)
    {
        $usergroup->update($request->all());

        // Associate Users to User's group
        if ($request->users) {
            $usergroup->users()->sync($request->users);
        } else {
            $usergroup->users()->detach();
        }
        $usergroup->save();

        flash()->success(\Lang::get('usergroup/messages.edit.success'));

        return redirect()->route('usergroups.index');
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

    /**
     * Return all Usergroups in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (! \Request::ajax()) {
            \App::abort(403);
        }

        $usergroups = Usergroup::select(array(
                'id', 'name', 'description'
        ))->orderBy('name', 'ASC');

        return $datatable->usingEloquent($usergroups)
            ->addColumn('users', function($model) {
                return count($model->users->lists('id')->all());
            })
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
