<?php

namespace SSHAM\Http\Controllers;

use SSHAM\FileEntry;
use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\UserRequest;
use SSHAM\User;
use SSHAM\Usergroup;
use yajra\Datatables\Datatables;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get all existing user groups
        $groups = Usergroup::lists('name', 'id')->all();
        return view('user.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $user = new User($request->all());

        if (!$request->publickey) {
            $privateKey = $user->createRSAKeyPair();
            // 'Download private key ' . link_to(route('file.download', $privateKey), 'here'),
        }
        $user->save();

        // Associate User's Groups
        $user->groups()->sync($request->usergroups);
        $user->save();

        flash()->overlay(\Lang::get('user/messages/create.success'));

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return Response
     */
    public function edit(User $user)
    {
        // Get all existing user groups
        $groups = Usergroup::lists('name', 'id')->all();
        return view('user.edit', compact('user', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User $user
     * @param UserRequest $request
     * @return Response
     */
    public function update(User $user, UserRequest $request)
    {
        $user->update($request->all());

        // Associate User's Groups
        $user->groups()->sync($request->usergroups);
        $user->save();

        flash()->success(\Lang::get('user/messages.edit.success'));

        return redirect()->route('users.edit', [$user->id]);
    }

    /**
     * Remove user.
     *
     * @param User $user
     * @return Response
     */
    public function delete(User $user)
    {
        return view('user.delete', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        flash()->success(\Lang::get('user/messages.delete.success'));

        return redirect()->route('users.index');
    }

    /**
     * Return all Users in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return mixed
     */
    public function data(Datatables $datatable)
    {
        $users = User::select(array(
            'id', 'name', 'fingerprint', 'active'
        ));

        return $datatable->usingEloquent($users)
            ->editColumn('name', function ($model) {
                return ($model->active) ? $model->name : $model->name . ' <span class="label label-sm label-danger">Inactivo</span>';
            })
            ->addColumn('groups', function ($model) {
                return count($model->groups->lists('id')->all());
            })
            ->addColumn('actions', function ($model) {
                return view('partials.actions_dd', array(
                    'model' => 'users',
                    'id' => $model->id
                ))->render();
            })
            ->removeColumn('id')
            ->removeColumn('active')
            ->make(true);
    }

}
