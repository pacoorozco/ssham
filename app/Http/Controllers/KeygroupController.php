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
use App\Key;
use App\Keygroup;
use yajra\Datatables\Datatables;

class KeygroupController extends Controller
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
        return view('keygroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing keys
        $keys = Key::orderBy('username')->pluck('username', 'id');

        return view('keygroup.create', compact('keys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KeygroupCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(KeygroupCreateRequest $request)
    {
        try {
            $group = Keygroup::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate Keys to Key groups
            if ($request->keys) {
                $group->keys()->sync($request->keys);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(__('keygroup/messages.create.error'));
        }

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.create.success', ['name' => $group->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param Keygroup $keygroup
     *
     * @return \Illuminate\View\View
     */
    public function show(Keygroup $keygroup)
    {
        return view('keygroup.show', compact('keygroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Keygroup $keygroup
     *
     * @return \Illuminate\View\View
     */
    public function edit(Keygroup $keygroup)
    {
        // Get all existing keys
        $keys = Key::orderBy('username')->pluck('username', 'id');

        return view('keygroup.edit', compact('keygroup', 'keys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Keygroup              $keygroup
     * @param KeygroupUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Keygroup $keygroup, KeygroupUpdateRequest $request)
    {
        try {
            $keygroup->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Associate Users to User's group
            if ($request->keys) {
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

    /**
     * Remove keygroup.
     *
     * @param Keygroup $keygroup
     *
     * @return \Illuminate\View\View
     */
    public function delete(Keygroup $keygroup)
    {
        return view('keygroup.delete', compact('keygroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Keygroup $group
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Keygroup $group)
    {
        $name = $group->name;

        try {
            $group->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(__('keygroup/messages.delete.error'));
        }

        return redirect()->route('keygroups.index')
            ->withSuccess(__('keygroup/messages.delete.success', ['name' => $name]));
    }

    /**
     * Return all keygroups in order to be used as Datatables
     *
     * @param Datatables $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $groups = Keygroup::select([
            'id',
            'name',
            'description',
        ])
            ->withCount('keys as keys') // count number of keys in keygroups without loading the models
            ->withCount('rules as rules') // count number of keys in rules without loading the models
            ->orderBy('name', 'asc');

        return $datatable->eloquent($groups)
            ->editColumn('rules', function (Keygroup $group) {
                return trans_choice('rule/model.items_count', $group->rules, ['value' => $group->rules]);
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
