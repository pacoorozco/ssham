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

use App\Helpers\Helper;
use App\Http\Requests\HostCreateRequest;
use App\Http\Requests\HostUpdateRequest;
use App\Models\Activity;
use App\Models\Host;
use App\Models\Hostgroup;
use yajra\Datatables\Datatables;

class HostController extends Controller
{
    /**
     * Create a new controller instance.
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
        return view('host.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing host groups
        $groups = Hostgroup::orderBy('name')->pluck('name', 'id');

        return view('host.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  HostCreateRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HostCreateRequest $request)
    {
        try {
            $host = Host::create([
                'hostname' => $request->hostname,
                'username' => $request->username,
                'port' => $request->port,
                'authorized_keys_file' => $request->authorized_keys_file,
            ]);

            // Associate Host's Groups
            if (!empty($request->groups)) {
                $host->groups()->sync($request->groups);
            }
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->withError(__('host/messages.create.error'));
        }

        activity()
            ->performedOn($host)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create host '%s@%s'.", $host->username, $host->hostname));

        return redirect()->route('hosts.index')
            ->withSuccess(__('host/messages.create.success', ['hostname' => $host->full_hostname]));
    }

    /**
     * Display the specified resource.
     *
     * @param  Host  $host
     *
     * @return \Illuminate\View\View
     */
    public function show(Host $host)
    {
        return view('host.show', compact('host'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Host  $host
     *
     * @return \Illuminate\View\View
     */
    public function edit(Host $host)
    {
        // Get all existing host groups
        $groups = Hostgroup::orderBy('name')->pluck('name', 'id');

        return view('host.edit', compact('host', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Host  $host
     * @param  HostUpdateRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Host $host, HostUpdateRequest $request)
    {
        try {
            $host->update([
                'port' => $request->port,
                'authorized_keys_file' => $request->authorized_keys_file,
                'enabled' => $request->enabled,
            ]);

            // Associate Host's Groups
            if (empty($request->groups)) {
                $host->groups()->detach();
            } else {
                $host->groups()->sync($request->groups);
            }
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->withError(__('host/messages.edit.error'));
        }

        activity()
            ->performedOn($host)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Update host '%s@%s'.", $host->username, $host->hostname));

        return redirect()->route('hosts.edit', [$host->id])
            ->withSuccess(__('host/messages.edit.success', ['hostname' => $host->full_hostname]));
    }

    /**
     * Remove host.
     *
     * @param  Host  $host
     *
     * @return \Illuminate\View\View
     */
    public function delete(Host $host)
    {
        return view('host.delete', compact('host'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Host  $host
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Host $host)
    {
        $hostname = $host->full_hostname;

        try {
            $host->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withError(__('host/messages.delete.error'));
        }

        activity()
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Delete host '%s@%s'.", $host->username, $host->hostname));

        return redirect()->route('hosts.index')
            ->withSuccess(__('host/messages.delete.success', ['hostname' => $hostname]));
    }

    /**
     * Return all Hosts in order to be used as DataTables.
     *
     * @param  Datatables  $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $hosts = Host::select([
            'id',
            'hostname',
            'username',
            'type',
            'enabled',
        ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('hostname', 'asc');

        return $datatable->eloquent($hosts)
            ->editColumn('enabled', function (Host $host) {
                return Helper::addStatusLabel($host->enabled);
            })
            ->addColumn('actions', function (Host $host) {
                return view('partials.actions_dd')
                    ->with('model', 'hosts')
                    ->with('id', $host->id)
                    ->render();
            })
            ->rawColumns(['hostname', 'enabled', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
