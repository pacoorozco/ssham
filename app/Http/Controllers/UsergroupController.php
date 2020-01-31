<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Http\Requests\UsergroupCreateRequest;
use App\Http\Requests\UsergroupUpdateRequest;
use App\User;
use App\Usergroup;
use Illuminate\Database\QueryException;
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('usergroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing users
        $users = User::orderBy('username')->pluck('username', 'id');

        return view('usergroup.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UsergroupCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UsergroupCreateRequest $request)
    {
        try {
            $usergroup = Usergroup::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate Users to User's group
            if ($request->users) {
                $usergroup->users()->sync($request->users);
            }
        } catch (QueryException $e) {
            return redirect()->back()->withInput()
                ->withErrors(__('usergroup/messages.create.error'));
        }


        return redirect()->route('usergroups.index')
            ->withSuccess(__('usergroup/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Usergroup $usergroup
     *
     * @return \Illuminate\View\View
     */
    public function show(Usergroup $usergroup)
    {
        return view('usergroup.show', compact('usergroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Usergroup $usergroup
     *
     * @return \Illuminate\View\View
     */
    public function edit(Usergroup $usergroup)
    {
        // Get all existing users
        $users = User::orderBy('username')->pluck('username', 'id');

        return view('usergroup.edit', compact('usergroup', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Usergroup              $usergroup
     * @param UsergroupUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Usergroup $usergroup, UsergroupUpdateRequest $request)
    {
        $usergroup->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Associate Users to User's group
        if ($request->users) {
            $usergroup->users()->sync($request->users);
        } else {
            $usergroup->users()->detach();
        }

        return redirect()->route('usergroups.edit', [$usergroup->id])
            ->withSuccess(__('usergroup/messages.edit.success'));
    }

    /**
     * Remove usergroup.
     *
     * @param Usergroup $usergroup
     *
     * @return \Illuminate\View\View
     */
    public function delete(Usergroup $usergroup)
    {
        return view('usergroup.delete', compact('usergroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Usergroup $usergroup
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Usergroup $usergroup)
    {
        $usergroup->delete();

        return redirect()->route('usergroups.index')
            ->withSuccess(__('usergroup/messages.delete.success'));
    }

    /**
     * Return all Usergroups in order to be used as Datatables
     *
     * @param Datatables $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $usergroups = Usergroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('users as users') // count number of users in usergroups without loading the models
            ->orderBy('name', 'asc');

        return $datatable->eloquent($usergroups)
            ->addColumn('actions', function (Usergroup $usergroup) {
                return view('partials.actions_dd')
                    ->with('model', 'usergroups')
                    ->with('id', $usergroup->id)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
