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

use App\Http\Requests\HostgroupCreateRequest;
use App\Http\Requests\HostgroupUpdateRequest;
use App\Models\Host;
use App\Models\Hostgroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use yajra\Datatables\Datatables;

class HostgroupController extends Controller
{
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

    public function store(HostgroupCreateRequest $request): RedirectResponse
    {
        try {
            $hostgroup = Hostgroup::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate Host's Groups
            if ($request->filled('hosts')) {
                $hostgroup->hosts()->sync($request->hosts);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('hostgroup/messages.create.error'));
        }

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

    public function update(Hostgroup $hostgroup, HostgroupUpdateRequest $request): RedirectResponse
    {
        try {
            $hostgroup->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate User's Groups
            if ($request->filled('hosts')) {
                $hostgroup->hosts()->sync($request->hosts);
            } else {
                $hostgroup->hosts()->detach();
            }
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('hostgroup/messages.edit.error'));
        }

        return redirect()->route('hostgroups.edit', [$hostgroup->id])
            ->withSuccess(__('hostgroup/messages.edit.success', ['name' => $hostgroup->name]));
    }

    public function delete(Hostgroup $hostgroup): View
    {
        return view('hostgroup.delete')
            ->with('hostgroup', $hostgroup);
    }

    public function destroy(Hostgroup $hostgroup): RedirectResponse
    {
        $name = $hostgroup->name;

        try {
            $hostgroup->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(__('hostgroup/messages.delete.error'));
        }

        return redirect()->route('hostgroups.index')
            ->withSuccess(__('hostgroup/messages.delete.success', ['name' => $name]));
    }

    public function data(Datatables $datatable): JsonResponse
    {
        $hostgroups = Hostgroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('hosts as hosts') // count number of hosts in hostgroups without loading the models
            ->orderBy('name', 'asc');

        return $datatable->eloquent($hostgroups)
            ->addColumn('rules', function (Hostgroup $group) {
                return $group->present()->rulesCount();
            })
            ->addColumn('actions', function (Hostgroup $hostgroup) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('model', 'hostgroups')
                    ->with('id', $hostgroup->id)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
