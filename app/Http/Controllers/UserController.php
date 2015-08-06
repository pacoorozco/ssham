<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\UserCreateRequest;
use SSHAM\Http\Requests\UserUpdateRequest;
use SSHAM\User;
use SSHAM\Usergroup;
use yajra\Datatables\Datatables;

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
     * @param UserCreateRequest $request
     * @return Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User($request->all());

//        if (!$request->public_key) {
//            $private_key = $user->createRSAKeyPair();
//            // 'Download private key ' . link_to(route('file.download', $private_key), 'here'),
//        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $request->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        $user->save();

        // Associate User's Groups if has been submitted
        if ($request->groups) {
            $user->usergroups()->attach($request->groups);
            $user->save();
        }

        flash()->overlay(\Lang::get('user/messages.create.success'));

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
     * @param UserUpdateRequest $request
     * @return Response
     */
    public function update(User $user, UserUpdateRequest $request)
    {
        $user->update($request->all());

        // Associate User's Groups
        if ($request->groups) {
            $user->usergroups()->sync($request->groups);
        } else {
            $user->usergroups()->detach();
        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $request->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (! \Request::ajax()) {
            \App::abort(403);
        }

        $users = User::select(array(
            'id', 'username', 'fingerprint', 'enabled'
        ))->orderBy('username', 'ASC');

        return $datatable->usingEloquent($users)
            ->editColumn('username', function ($model) {
                return ($model->enabled) ? $model->username : $model->username . ' <span class="label label-sm label-danger">' . \Lang::get('general.disabled') .'</span>';
            })
            ->addColumn('groups', function ($model) {
                return count($model->usergroups->lists('id')->all());
            })
            ->addColumn('actions', function ($model) {
                return view('partials.actions_dd', array(
                    'model' => 'users',
                    'id' => $model->id
                ))->render();
            })
            ->removeColumn('id')
            ->removeColumn('enabled')
            ->make(true);
    }

}
