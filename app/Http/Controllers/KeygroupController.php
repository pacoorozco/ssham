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

use App\Http\Requests\KeygroupCreateRequest;
use App\Http\Requests\KeygroupUpdateRequest;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use yajra\Datatables\Datatables;

class KeygroupController extends Controller
{
    public function index(): View
    {
        return view('keygroup.index');
    }

    public function create(): View
    {
        // Get all existing keys
        $keys = Key::orderBy('username')->pluck('username', 'id');

        return view('keygroup.create')
            ->with('keys', $keys);
    }

    public function store(KeygroupCreateRequest $request): RedirectResponse
    {
        try {
            $keygroup = Keygroup::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate Keys to Key groups
            if ($request->filled('keys')) {
                $keygroup->keys()->sync($request->keys);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('keygroup/messages.create.error'));
        }

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.create.success', ['name' => $keygroup->name]));
    }

    public function show(Keygroup $keygroup): View
    {
        return view('keygroup.show')
            ->with('keygroup', $keygroup);
    }

    public function edit(Keygroup $keygroup): View
    {
        // Get all existing keys
        $keys = Key::orderBy('username')->pluck('username', 'id');

        return view('keygroup.edit')
            ->with('keygroup', $keygroup)
            ->with('keys', $keys);
    }

    public function update(Keygroup $keygroup, KeygroupUpdateRequest $request): RedirectResponse
    {
        try {
            $keygroup->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate Users to User's group
            if ($request->filled('keys')) {
                $keygroup->keys()->sync($request->keys);
            } else {
                $keygroup->keys()->detach();
            }
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('keygroup/messages.edit.error'));
        }

        return redirect()->route('keygroups.edit', $keygroup->id)
            ->withSuccess(__('keygroup/messages.edit.success', ['name' => $keygroup->name]));
    }

    public function delete(Keygroup $keygroup): View
    {
        return view('keygroup.delete')
            ->with('keygroup', $keygroup);
    }

    public function destroy(Keygroup $keygroup): RedirectResponse
    {
        $name = $keygroup->name;

        try {
            $keygroup->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(__('keygroup/messages.delete.error'));
        }

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.delete.success', ['name' => $name]));
    }

    public function data(Datatables $datatable): JsonResponse
    {
        $keygroups = Keygroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('keys as keys') // count number of keys in keygroups without loading the models
            ->orderBy('name', 'asc');

        return $datatable->eloquent($keygroups)
            ->addColumn('rules', function (Keygroup $group) {
                return trans_choice('rule/model.items_count', $group->getNumberOfRelatedRules(),
                    ['value' => $group->getNumberOfRelatedRules()]);
            })
            ->addColumn('actions', function (Keygroup $keygroup) {
                return view('partials.actions_dd')
                    ->with('model', 'keygroups')
                    ->with('id', $keygroup->id)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
