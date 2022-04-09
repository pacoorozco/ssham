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

use App\Actions\CreateHostGroupAction;
use App\Actions\UpdateHostGroupAction;
use App\Http\Requests\HostgroupCreateRequest;
use App\Http\Requests\HostgroupUpdateRequest;
use App\Jobs\CreateHostgroup;
use App\Jobs\DeleteHostgroup;
use App\Jobs\UpdateHostgroup;
use App\Models\Host;
use App\Models\Hostgroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class HostgroupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Hostgroup::class, 'hostgroup');
    }

    public function index(): View
    {
        return view('hostgroup.index');
    }

    public function create(): View
    {
        // Get all existing hosts
        $hosts = Host::orderBy('hostname')->pluck('hostname', 'id');

        return view('hostgroup.create')
            ->with('hosts', $hosts);
    }

    public function store(HostgroupCreateRequest $request, CreateHostGroupAction $createHostGroup): RedirectResponse
    {
        $hostgroup = $createHostGroup(
            name: $request->name(),
            description: $request->description(),
            members: $request->hosts()
        );

        return redirect()->route('hostgroups.index')
            ->withSuccess(__('hostgroup/messages.create.success', ['name' => $hostgroup->name]));
    }

    public function show(Hostgroup $hostgroup): View
    {
        return view('hostgroup.show')
            ->with('hostgroup', $hostgroup);
    }

    public function edit(Hostgroup $hostgroup): View
    {
        // Get all existing hosts
        $hosts = Host::orderBy('hostname')->pluck('hostname', 'id');

        return view('hostgroup.edit')
            ->with('hostgroup', $hostgroup)
            ->with('hosts', $hosts);
    }

    public function update(Hostgroup $hostgroup, HostgroupUpdateRequest $request, UpdateHostGroupAction $updateHostGroup): RedirectResponse
    {
        $hostgroup = $updateHostGroup(
            group: $hostgroup,
            name: $request->name(),
            description: $request->description(),
            members: $request->hosts()
        );

        return redirect()->route('hostgroups.edit', [$hostgroup])
            ->withSuccess(__('hostgroup/messages.edit.success', ['name' => $hostgroup->name]));
    }

    public function destroy(Hostgroup $hostgroup): RedirectResponse
    {
        $name = $hostgroup->name;

        $hostgroup->delete();

        return redirect()->route('hostgroups.index')
            ->withSuccess(__('hostgroup/messages.delete.success', ['name' => $name]));
    }

    public function data(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Hostgroup::class);

        $hostgroups = Hostgroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('hosts as hosts') // count number of hosts in hostgroups without loading the models
            ->orderBy('name', 'asc');

        return $dataTable->eloquent($hostgroups)
            ->addColumn('rules', function (Hostgroup $group) {
                return $group->present()->rulesCount();
            })
            ->addColumn('actions', function (Hostgroup $hostgroup) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'hostgroups')
                    ->with('model', $hostgroup)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
