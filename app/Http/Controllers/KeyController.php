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

use App\Http\Requests\KeyCreateRequest;
use App\Http\Requests\KeyUpdateRequest;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PacoOrozco\OpenSSH\KeyPair;
use PacoOrozco\OpenSSH\PublicKey;
use Yajra\DataTables\DataTables;

class KeyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Key::class, 'key');
    }

    public function index(): View
    {
        return view('key.index');
    }

    public function create(): View
    {
        // Get all existing key groups
        $groups = Keygroup::orderBy('name')->pluck('name', 'id');

        return view('key.create')
            ->with('groups', $groups);
    }

    public function store(KeyCreateRequest $request): RedirectResponse
    {
        // Use transaction to ensure that the key is created with all the required data.
        DB::beginTransaction();

        try {
            if ($request->wantsCreateKey()) {
                [$privateKey, $publicKey] = (new KeyPair())->generate();
            } else {
                $publicKey = PublicKey::fromString($request->publicKey());
                $privateKey = null;
            }

            $key = Key::create([
                'username' => $request->username(),
                'public' => (string) $publicKey,
                'private' => (string) $privateKey,
            ]);

            $key->groups()->attach($request->groups());
        } catch (\Throwable $exception) {
            DB::rollBack(); // RollBack in case of error.

            Log::error("Key '{$request->username()}' was not created: {$exception->getMessage()}");

            return redirect()->back()
                ->withInput()
                ->withErrors(trans('key/messages.create.error'));
        }

        // Everything went fine, we can commit the transaction.
        DB::commit();

        return redirect()->route('keys.show', $key)
            ->with('success', __('key/messages.create.success', ['username' => $key->username]));
    }

    public function show(Key $key): View
    {
        return view('key.show')
            ->with('key', $key);
    }

    public function edit(Key $key): View
    {
        // Get all existing key groups
        $groups = Keygroup::orderBy('name')->pluck('name', 'id');

        return view('key.edit')
            ->with('key', $key)
            ->with('groups', $groups);
    }

    public function update(Key $key, KeyUpdateRequest $request): RedirectResponse
    {
        // Use transaction to ensure that the key is updated with all the required data.
        DB::beginTransaction();

        try {
            $privateKey = $key->private;
            $publicKey = $key->public;

            if ($request->wantsCreateKey()) {
                [$privateKey, $publicKey] = (new KeyPair())->generate();
            } elseif ($request->wantsImportKey()) {
                $publicKey = PublicKey::fromString($request->publicKey());
            }

            $key->update([
                'enabled' => $request->enabled(),
                'private' => $privateKey,
                'public' => $publicKey,
            ]);

            $key->groups()->sync(collect($request->groups()));
        } catch (\Throwable $exception) {
            DB::rollBack(); // RollBack in case of error.

            Log::error("Key '{$key->username}' was not updated: {$exception->getMessage()}");

            return redirect()->back()
                ->withInput()
                ->withErrors(trans('key/messages.edit.error'));
        }

        // Everything went fine, we can commit the transaction.
        DB::commit();

        return redirect()->route('keys.show', $key)
            ->with('success', __('key/messages.edit.success', ['username' => $key->username]));
    }

    public function destroy(Key $key): RedirectResponse
    {
        $username = $key->username;

        try {
            $key->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(trans('key/messages.delete.error'));
        }

        return redirect()->route('keys.index')
            ->with('success', __('key/messages.delete.success', ['username' => $username]));
    }

    public function data(DataTables $dataTable): JsonResponse
    {
        $this->authorize('viewAny', Key::class);

        $keys = Key::select([
            'id',
            'username',
            'fingerprint',
            'enabled',
        ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('username', 'asc');

        return $dataTable->eloquent($keys)
            ->editColumn('username', function (Key $key) {
                return $key->present()->usernameWithDisabledBadge();
            })
            ->editColumn('enabled', function (Key $key) {
                return $key->present()->enabledAsBadge();
            })
            ->addColumn('actions', function (Key $key) {
                return view('partials.buttons-to-show-and-edit-actions')
                    ->with('modelType', 'keys')
                    ->with('model', $key)
                    ->render();
            })
            ->rawColumns(['username', 'enabled', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
