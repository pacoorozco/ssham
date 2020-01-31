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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2020 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;


use App\Host;
use App\Hostgroup;
use App\Http\Requests\HostgroupCreateRequest;
use App\Http\Requests\HostgroupUpdateRequest;
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('hostgroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing hosts
        $hosts = Host::orderBy('hostname')->pluck('hostname', 'id');

        return view('hostgroup.create', compact('hosts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HostgroupCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HostgroupCreateRequest $request)
    {
        $hostgroup = Hostgroup::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Associate Host's Groups
        if ($request->hosts) {
            $hostgroup->hosts()->sync($request->hosts);
        }

        return redirect()->route('hostgroups.index')
            ->withSuccess(__('hostgroup/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Hostgroup $hostgroup
     *
     * @return \Illuminate\View\View
     */
    public function show(Hostgroup $hostgroup)
    {

        return view('hostgroup.show', compact('hostgroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Hostgroup $hostgroup
     *
     * @return \Illuminate\View\View
     */
    public function edit(Hostgroup $hostgroup)
    {
        // Get all existing hosts
        $hosts = Host::orderBy('hostname')->pluck('hostname', 'id');

        return view('hostgroup.edit', compact('hostgroup', 'hosts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Hostgroup              $hostgroup
     * @param HostgroupUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Hostgroup $hostgroup, HostgroupUpdateRequest $request)
    {
        $hostgroup->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Associate User's Groups
        if ($request->hosts) {
            $hostgroup->hosts()->sync($request->hosts);
        } else {
            $hostgroup->hosts()->detach();
        }

        return redirect()->route('hostgroups.edit', [$hostgroup->id])
            ->withSuccess(__('hostgroup/messages.edit.success'));
    }

    /**
     * Remove hostgroup.
     *
     * @param Hostgroup $hostgroup
     *
     * @return \Illuminate\View\View
     */
    public function delete(Hostgroup $hostgroup)
    {
        return view('hostgroup.delete', compact('hostgroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Hostgroup $hostgroup
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Hostgroup $hostgroup)
    {
        $hostgroup->delete();

        return redirect()->route('hostgroups.index')
            ->withSuccess(__('hostgroup/messages.delete.success'));
    }

    /**
     * Return all Hostgroups in order to be used as DataTables
     *
     * @param Datatables $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $hostgroups = Hostgroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('hosts as hosts') // count number of hosts in hostgroups without loading the models
            ->orderBy('name', 'asc');

        return $datatable->eloquent($hostgroups)
            ->addColumn('actions', function (Hostgroup $hostgroup) {
                return view('partials.actions_dd')
                    ->with('model', 'hostgroups')
                    ->with('id', $hostgroup->id)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
