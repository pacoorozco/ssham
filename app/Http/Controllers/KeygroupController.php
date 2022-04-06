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

use App\Http\Requests\KeygroupCreateRequest;
use App\Http\Requests\KeygroupUpdateRequest;
use App\Jobs\CreateKeygroup;
use App\Jobs\DeleteKeygroup;
use App\Jobs\UpdateKeygroup;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class KeygroupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Keygroup::class, 'keygroup');
    }

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
        $keygroup = CreateKeygroup::dispatchSync(
            $request->name(),
            $request->description(),
            $request->keys(),
        );

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
        UpdateKeygroup::dispatchSync(
            $keygroup,
            $request->name(),
            $request->description(),
            $request->keys(),
        );

        return redirect()->route('keygroups.edit', $keygroup->id)
            ->withSuccess(__('keygroup/messages.edit.success', ['name' => $keygroup->name]));
    }

    public function destroy(Keygroup $keygroup): RedirectResponse
    {
        DeleteKeygroup::dispatchSync($keygroup);

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.delete.success', ['name' =>$keygroup->name]));
    }

    public function data(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Keygroup::class);

        $keygroups = Keygroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('keys as keys') // count number of keys in keygroups without loading the models
            ->orderBy('name', 'asc');

        return $dataTable->eloquent($keygroups)
            ->addColumn('rules', function (Keygroup $group) {
                return $group->present()->rulesCount();
            })
            ->addColumn('actions', function (Keygroup $keygroup) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'keygroups')
                    ->with('model', $keygroup)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
