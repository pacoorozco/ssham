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

use App\Actions\CreateKeysGroupAction;
use App\Actions\UpdateKeysGroupAction;
use App\Http\Requests\KeygroupCreateRequest;
use App\Http\Requests\KeygroupUpdateRequest;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        $keys = Key::orderBy('name')->pluck('name', 'id');

        return view('keygroup.create')
            ->with('keys', $keys);
    }

    public function store(
        KeygroupCreateRequest $request,
        CreateKeysGroupAction $createKeysGroup
    ): RedirectResponse {
        $group = $createKeysGroup(
            name: $request->name(),
            description: $request->description(),
            members: $request->keys(),
        );

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.create.success', ['name' => $group->name]));
    }

    public function show(Keygroup $keygroup): View
    {
        return view('keygroup.show')
            ->with('keygroup', $keygroup);
    }

    public function edit(Keygroup $keygroup): View
    {
        // Get all existing keys
        $keys = Key::orderBy('name')->pluck('name', 'id');

        return view('keygroup.edit')
            ->with('keygroup', $keygroup)
            ->with('keys', $keys);
    }

    public function update(
        Keygroup $keygroup,
        KeygroupUpdateRequest $request,
        UpdateKeysGroupAction $updateKeysGroup
    ): RedirectResponse {
        $updateKeysGroup(
            group: $keygroup,
            name: $request->name(),
            description: $request->description(),
            members: $request->keys(),
        );

        return redirect()->route('keygroups.edit', $keygroup)
            ->withSuccess(__('keygroup/messages.edit.success', ['name' => $keygroup->name]));
    }

    public function destroy(Keygroup $keygroup): RedirectResponse
    {
        $name = $keygroup->name;

        $keygroup->delete();

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.delete.success', ['name' => $name]));
    }
}
