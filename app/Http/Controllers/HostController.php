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
use SSHAM\Http\Requests\HostCreateRequest;
use SSHAM\Http\Requests\HostUpdateRequest;
use SSHAM\Host;
use SSHAM\Hostgroup;
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
     * @return View
     */
    public function index()
    {
        return view('host.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        // Get all existing user groups
        $groups = Hostgroup::pluck('name', 'id')->all();

        return view('host.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HostCreateRequest $request
     * @return RedirectResponse
     */
    public function store(HostCreateRequest $request)
    {
        $host = new Host($request->all());
        $host->save();

        // Associate Host's Groups
        if ($request->groups) {
            $host->hostgroups()->sync($request->groups);
            $host->save();
        }

        flash()->success(trans('host/messages.create.success'));

        return redirect()->route('hosts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Host $host
     * @return View
     */
    public function show(Host $host)
    {
        return view('host.show', compact('host'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Host $host
     * @return View
     */
    public function edit(Host $host)
    {
        // Get all existing user groups
        $groups = Hostgroup::pluck('name', 'id')->all();

        return view('host.edit', compact('host', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Host $host
     * @param HostUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(Host $host, HostUpdateRequest $request)
    {
        $host->update($request->all());

        // Associate Host's Groups
        if ($request->groups) {
            $host->hostgroups()->sync($request->groups);
        } else {
            $host->hostgroups()->detach();
        }
        $host->save();

        flash()->success(trans('host/messages.edit.success'));

        return redirect()->route('hosts.edit', [$host->id]);
    }

    /**
     * Remove host.
     *
     * @param Host $host
     * @return View
     */
    public function delete(Host $host)
    {
        return view('host.delete', compact('host'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Host $host
     * @return RedirectResponse
     */
    public function destroy(Host $host)
    {
        $host->delete();

        flash()->success(trans('host/messages.delete.success'));

        return redirect()->route('hosts.index');
    }

    /**
     * Return all Hosts in order to be used as Datatables
     *
     * @param Datatables $datatable
     * @return JsonResponse
     */
    public function data(Datatables $datatable)
    {
        if (!Request::ajax()) {
            abort(403);
        }

        $hosts = Host::select(array(
            'id', 'hostname', 'username', 'type', 'enabled'
        ))->orderBy('hostname', 'ASC');

        return $datatable->usingEloquent($hosts)
            ->editColumn('enabled', function (Host $host) {
                return ($host->enabled) ? '<span class="label label-sm label-success">' . trans('general.enabled') . '</span>'
                    : '<span class="label label-sm label-danger">' . trans('general.disabled') . '</span>';
            })
            ->addColumn('groups', function (Host $host) {
                return count($host->hostgroups->pluck('id')->all());
            })
            ->addColumn('actions', function (Host $host) {
                return view('partials.actions_dd', array(
                    'model' => 'hosts',
                    'id'    => $host->id
                ))->render();
            })
            ->removeColumn('id')
            ->make(true);
    }

}
