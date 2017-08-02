<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Request;
use SSHAM\Http\Requests\UsergroupCreateRequest;
use SSHAM\Http\Requests\UsergroupUpdateRequest;
use SSHAM\User;
use SSHAM\Usergroup;
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
     * @return View
     */
    public function index()
    {
        return view('usergroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        // Get all existing users
        $users = User::pluck('username', 'id')->all();

        return view('usergroup.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UsergroupCreateRequest $request
     * @return RedirectResponse
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

        flash()->success(trans('usergroup/messages.create.success'));

        return redirect()->route('usergroups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Usergroup $usergroup
     * @return View
     */
    public function show(Usergroup $usergroup)
    {
        return view('usergroup.show', compact('usergroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Usergroup $usergroup
     * @return View
     */
    public function edit(Usergroup $usergroup)
    {
        // Get all existing users
        $users = User::pluck('username', 'id')->all();

        return view('usergroup.edit', compact('usergroup', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Usergroup $usergroup
     * @param UsergroupUpdateRequest $request
     * @return RedirectResponse
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

        flash()->success(trans('usergroup/messages.edit.success'));

        return redirect()->route('usergroups.index');
    }

    /**
     * Remove usergroup.
     *
     * @param Usergroup $usergroup
     * @return View
     */
    public function delete(Usergroup $usergroup)
    {
        return view('usergroup.delete', compact('usergroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Usergroup $usergroup
     * @return RedirectResponse
     */
    public function destroy(Usergroup $usergroup)
    {
        $usergroup->delete();

        flash()->success(trans('usergroup/messages.delete.success'));

        return redirect()->route('usergroups.index');
    }

    /**
     * Return all Usergroups in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (!Request::ajax()) {
            abort(403);
        }

        $usergroups = Usergroup::select(array(
            'id', 'name', 'description'
        ))->orderBy('name', 'ASC');

        return $datatable->usingEloquent($usergroups)
            ->addColumn('users', function (Usergroup $usergroup) {
                return count($usergroup->users->pluck('id')->all());
            })
            ->addColumn('actions', function (Usergroup $usergroup) {
                return view('partials.actions_dd', array(
                    'model' => 'usergroups',
                    'id'    => $usergroup->id
                ))->render();
            })
            ->removeColumn('id')
            ->make(true);
    }

}
