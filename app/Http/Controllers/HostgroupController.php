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
use SSHAM\Host;
use SSHAM\Hostgroup;
use SSHAM\Http\Requests\HostgroupCreateRequest;
use SSHAM\Http\Requests\HostgroupUpdateRequest;
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
     * @return View
     */
    public function index()
    {
        return view('hostgroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        // Get all existing hosts
        $hosts = Host::pluck('hostname', 'id')->all();

        return view('hostgroup.create', compact('hosts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HostgroupCreateRequest $request
     * @return RedirectResponse
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

        flash()->success(trans('hostgroup/messages.create.success'));

        return redirect()->route('hostgroups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Hostgroup $hostgroup
     * @return View
     */
    public function show(Hostgroup $hostgroup)
    {

        return view('hostgroup.show', compact('hostgroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Hostgroup $hostgroup
     * @return View
     */
    public function edit(Hostgroup $hostgroup)
    {
        // Get all existing hosts
        $hosts = Host::pluck('hostname', 'id')->all();

        return view('hostgroup.edit', compact('hostgroup', 'hosts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Hostgroup $hostgroup
     * @param HostgroupUpdateRequest $request
     * @return RedirectResponse
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

        flash()->success(trans('hostgroup/messages.edit.success'));

        return redirect()->route('hostgroups.edit', [$hostgroup->id]);
    }

    /**
     * Remove hostgroup.
     *
     * @param Hostgroup $hostgroup
     * @return View
     */
    public function delete(Hostgroup $hostgroup)
    {
        return view('hostgroup.delete', compact('hostgroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Hostgroup $hostgroup
     * @return RedirectResponse
     */
    public function destroy(Hostgroup $hostgroup)
    {
        $hostgroup->delete();

        flash()->success(trans('hostgroup/messages.delete.success'));

        return redirect()->route('hostgroups.index');
    }

    /**
     * Return all Hostgroups in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (!Request::ajax()) {
            abort(403);
        }

        $hostgroups = Hostgroup::select(array(
            'id', 'name', 'description'
        ))->orderBy('name', 'ASC');

        return $datatable->usingEloquent($hostgroups)
            ->addColumn('hosts', function (Hostgroup $hostgroup) {
                return count($hostgroup->hosts->pluck('id')->all()); ;
            })
            ->addColumn('actions', function (Hostgroup $hostgroup) {
                return view('partials.actions_dd', array(
                    'model' => 'hostgroups',
                    'id'    => $hostgroup->id
                ))->render();
            })
            ->removeColumn('id')
            ->make(true);
    }

}
