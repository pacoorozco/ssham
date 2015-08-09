<?php

namespace SSHAM\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Request;
use SSHAM\Http\Requests\UserCreateRequest;
use SSHAM\Http\Requests\UserUpdateRequest;
use SSHAM\User;
use SSHAM\Usergroup;
use yajra\Datatables\Datatables;

class UserController extends Controller {

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
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
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
     * @return RedirectResponse
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User($request->all());

        // Test if we need to create a new RSA key
        if ($request->create_rsa_key == '1') {
            list($request->public_key, $private_key) = $user->createRSAKeyPair();
        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $request->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        $user->save();

        // Associate User's Groups if has been submitted
        if ($request->groups) {
            $user->usergroups()->attach($request->groups);
            $user->save();
        }

        if ($request->create_rsa_key == '1') {
            flash()->overlay(trans('user/messages.create.success_private', array('url' => link_to(route('file.download', $private_key), 'this link'))));
        } else {
            flash()->overlay(trans('user/messages.create.success'));
        }

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return View
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
     * @return RedirectResponse
     */
    public function update(User $user, UserUpdateRequest $request)
    {
        $private_key = null;
        $user->update($request->all());

        // Test if we need to create a new RSA key
        if ($request->create_rsa_key == '1') {
            list($request->public_key, $private_key) = $user->createRSAKeyPair();
        }

        // Calculates fingerprint of a SSH public key
        $content = explode(' ', $request->public_key, 3);
        $user->fingerprint = join(':', str_split(md5(base64_decode($content[1])), 2));

        $user->save();

        // Associate User's Groups
        if ($request->groups) {
            $user->usergroups()->sync($request->groups);
        } else {
            $user->usergroups()->detach();
        }

        $user->save();

        if ($request->create_rsa_key == '1' && ! is_null($private_key)) {
            flash()->overlay(trans('user/messages.edit.success_private', array('url' => link_to(route('file.download', ['filename' => $private_key]), 'this link'))));
        } else {
            flash()->overlay(trans('user/messages.edit.success'));
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove user.
     *
     * @param User $user
     * @return View
     */
    public function delete(User $user)
    {
        return view('user.delete', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        flash()->success(trans('user/messages.delete.success'));

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
        if ( ! Request::ajax()) {
            abort(403);
        }

        $users = User::select(array(
            'id', 'username', 'fingerprint', 'enabled'
        ))->orderBy('username', 'ASC');

        return $datatable->usingEloquent($users)
            ->editColumn('username', function (User $user) {
                return ($user->enabled) ? $user->username : $user->username . ' <span class="label label-sm label-danger">' . trans('general.disabled') . '</span>';
            })
            ->addColumn('groups', function (User $user) {
                return count($user->usergroups->lists('id')->all());
            })
            ->addColumn('actions', function (User $user) {
                return view('partials.actions_dd', array(
                    'model' => 'users',
                    'id'    => $user->id
                ))->render();
            })
            ->removeColumn('id')
            ->removeColumn('enabled')
            ->make(true);
    }

}
