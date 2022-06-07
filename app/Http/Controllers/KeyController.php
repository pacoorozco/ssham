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

use App\Actions\CreateKeyAction;
use App\Actions\UpdateKeyAction;
use App\Http\Requests\KeyCreateRequest;
use App\Http\Requests\KeyUpdateRequest;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PacoOrozco\OpenSSH\PrivateKey;
use PacoOrozco\OpenSSH\PublicKey;
use Throwable;

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
        $groups = Keygroup::query()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('key.create')
            ->with('groups', $groups);
    }

    public function store(KeyCreateRequest $request, CreateKeyAction $createKey): RedirectResponse
    {
        if ($request->wantsCreateKey()) {
            $privateKey = PrivateKey::generate();
            $publicKey = $privateKey->getPublicKey();
        } else {
            $publicKey = PublicKey::fromString($request->publicKey());
            $privateKey = null;
        }

        $key = $createKey(
            username: $request->username(),
            publicKey: $publicKey,
            privateKey: $privateKey,
            groups: $request->groups()
        );

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
        $groups = Keygroup::query()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('key.edit')
            ->with('key', $key)
            ->with('groups', $groups);
    }

    public function update(Key $key, KeyUpdateRequest $request, UpdateKeyAction $updateKey): RedirectResponse
    {
        $privateKey = $key->private;
        $publicKey = $key->public;

        if ($request->wantsCreateKey()) {
            $privateKey = PrivateKey::generate();
            $publicKey = $privateKey->getPublicKey();
        } elseif ($request->wantsImportKey()) {
            $publicKey = PublicKey::fromString($request->publicKey());
            $privateKey = null;
        }

        $updateKey(
            key: $key,
            publicKey: $publicKey,
            privateKey: $privateKey,
            groups: $request->groups(),
            enabled: $request->enabled(),
        );

        return redirect()->route('keys.show', $key)
            ->with('success', __('key/messages.edit.success', ['username' => $key->username]));
    }

    public function destroy(Key $key): RedirectResponse
    {
        $username = $key->username;

        try {
            $key->delete();
        } catch (Throwable $exception) {
            Log::error("Key '$key->username' was not deleted: {$exception->getMessage()}");

            return redirect()->back()
                ->withErrors(trans('key/messages.delete.error'));
        }

        return redirect()->route('keys.index')
            ->with('success', __('key/messages.delete.success', ['username' => $username]));
    }
}
