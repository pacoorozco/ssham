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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Actions\CreateHostAction;
use App\Actions\UpdateHostAction;
use App\Actions\UpdateUserAction;
use App\Http\Requests\HostCreateRequest;
use App\Http\Requests\HostUpdateRequest;
use App\Jobs\CreateHost;
use App\Jobs\DeleteHost;
use App\Jobs\UpdateHost;
use App\Models\Host;
use App\Models\Hostgroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class HostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Host::class, 'host');
    }

    public function index(): View
    {
        return view('host.index');
    }

    public function create(): View
    {
        // Get all existing host groups
        $groups = Hostgroup::orderBy('name')->pluck('name', 'id');

        return view('host.create')
            ->with('groups', $groups);
    }

    public function store(HostCreateRequest $request, CreateHostAction $createHost): RedirectResponse
    {
        $host = $createHost(
            $request->hostname(),
            $request->username(),
            [
                'enabled' => $request->enabled(),
                'port' => $request->port(),
                'authorized_keys_file' => $request->authorized_keys_file(),
                'groups' => $request->groups(),
            ]);

        return redirect()->route('hosts.index')
            ->withSuccess(__('host/messages.create.success', ['hostname' => $host->full_hostname]));
    }

    public function show(Host $host): View
    {
        return view('host.show')
            ->with('host', $host);
    }

    public function edit(Host $host): View
    {
        // Get all existing host groups
        $groups = Hostgroup::orderBy('name')->pluck('name', 'id');

        return view('host.edit')
            ->with('host', $host)
            ->with('groups', $groups);
    }

    public function update(Host $host, HostUpdateRequest $request, UpdateHostAction $updateHost): RedirectResponse
    {
        $updateHost(
            $host,
            [
                'enabled' => $request->enabled(),
                'port' => $request->port(),
                'authorized_keys_file' => $request->authorized_keys_file(),
                'groups' => $request->groups(),
            ]
        );

        return redirect()->route('hosts.index')
            ->withSuccess(__('host/messages.edit.success', ['hostname' => $host->full_hostname]));
    }

    public function destroy(Host $host): RedirectResponse
    {
        DeleteHost::dispatchSync($host);

        return redirect()->route('hosts.index')
            ->withSuccess(__('host/messages.delete.success', ['hostname' => $host->hostname]));
    }

    public function data(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Host::class);

        $hosts = Host::select([
            'id',
            'hostname',
            'username',
            'synced',
            'status_code',
            'enabled',
        ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('hostname', 'asc');

        return $dataTable->eloquent($hosts)
            ->editColumn('enabled', function (Host $host) {
                return $host->present()->enabledAsBadge();
            })
            ->editColumn('synced', function (Host $host) {
                return $host->present()->pendingSyncAsBadge();
            })
            ->editColumn('status_code', function (Host $host) {
                return $host->present()->statusCode();
            })
            ->addColumn('actions', function (Host $host) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'hosts')
                    ->with('model', $host)
                    ->render();
            })
            ->rawColumns(['enabled', 'synced', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
