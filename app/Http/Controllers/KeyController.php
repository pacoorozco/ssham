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
use App\Http\Requests\KeyCreateRequest;
use App\Http\Requests\KeyUpdateRequest;
use App\Key;
use App\Keygroup;
use App\Libs\RsaSshKey\RsaSshKey;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use yajra\Datatables\Datatables;

class KeyController extends Controller
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
        return view('key.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing key groups
        $groups = Keygroup::orderBy('name')->pluck('name', 'id');

        return view('key.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KeyCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(KeyCreateRequest $request)
    {
        // Use transaction to ensure that the key is created with all the required data.
        DB::beginTransaction();

        $public_key = $request->public_key_input;
        $private_key = null;

        // Test if we need to create a new RSA key
        if ($request->public_key == 'create') {
            $keys = RsaSshKey::create();
            $public_key = $keys['publickey'];
            $private_key = $keys['privatekey'];
        }

        try {
            $key = Key::create([
                'username' => $request->username,
            ]);

            // Associate User's Groups if has been submitted
            $key->groups()->attach($request->groups);

            // Attach the RSA SSH public key to the created use (includes a save() method).
            $key->attachKeyAndSave($public_key, $private_key);
        } catch (\Throwable $exception) {
            DB::rollBack(); // RollBack in case of error.
            return redirect()->back()
                ->withInput()
                ->withErrors(__('key/messages.create.error'));
        }

        // Everything went fine, we can commit the transaction.
        DB::commit();

        return redirect()->route('keys.index')
            ->withSuccess(__('key/messages.create.success', ['username' => $key->username]));
    }

    /**
     * Display the specified resource.
     *
     * @param Key $key
     *
     * @return \Illuminate\View\View
     */
    public function show(Key $key)
    {
        return view('key.show', compact('key'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Key $key
     *
     * @return \Illuminate\View\View
     */
    public function edit(Key $key)
    {
        // Get all existing key groups
        $groups = Keygroup::orderBy('name')->pluck('name', 'id');

        return view('key.edit', compact('key', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Key              $key
     * @param KeyUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Key $key, KeyUpdateRequest $request)
    {
        // Use transaction to ensure that the key is updated with all the required data.
        DB::beginTransaction();

        try {
            $key->update([
                'enabled' => $request->enabled,
            ]);

            // Associate Key's Groups
            if ($request->groups) {
                $key->groups()->sync($request->groups);
            } else {
                $key->groups()->detach();
            }

            // Test if we need to create a new RSA key
            $private_key = null;
            switch ($request->public_key) {
                case 'create':
                    $keys = RsaSshKey::create();
                    $public_key = $keys['publickey'];
                    $private_key = $keys['privatekey'];
                    break;
                case 'import':
                    $public_key = $request->public_key_input;
                    break;
                case 'maintain':
                default:
                    $public_key = null;
                    break;
            }

            // Attach the RSA SSH public key to the created use (includes a save() method).
            if (!empty($public_key)) {
                $key->attachKeyAndSave($public_key, $private_key);
            }
        } catch (\Throwable $exception) {
            DB::rollBack(); // RollBack in case of error.
            return redirect()->back()
                ->withInput()
                ->withErrors(__('key/messages.edit.error'));
        }

        // Everything went fine, we can commit the transaction.
        DB::commit();

        return redirect()->route('keys.index')
            ->withSuccess(__('key/messages.edit.success', ['username' => $key->username]));
    }

    /**
     * Remove key.
     *
     * @param Key $key
     *
     * @return \Illuminate\View\View
     */
    public function delete(Key $key)
    {
        return view('key.delete', compact('key'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Key $key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Key $key)
    {
        $username = $key->username;

        try {
            $key->delete();
        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(__('key/messages.delete.error'));
        }

        return redirect()->route('keys.index')
            ->withSuccess(__('key/messages.delete.success', ['username' => $username]));
    }

    /**
     * Return all Key in order to be used with DataTables
     *
     * @param Datatables $datatable
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Datatables $datatable)
    {
        $keys = Key::select([
            'id',
            'username',
            'fingerprint',
            'enabled',
        ])
            ->withCount('groups as groups') // count number of groups without loading the models
            ->orderBy('username', 'asc');

        return $datatable->eloquent($keys)
            ->editColumn('username', function (Key $key) {
                return Helper::addDisabledStatusLabel($key->enabled, $key->username);
            })
            ->addColumn('actions', function (Key $key) {
                return view('partials.actions_dd')
                    ->with('model', 'keys')
                    ->with('id', $key->id)
                    ->render();
            })
            ->rawColumns(['username', 'actions'])
            ->removeColumn('id')
            ->removeColumn('enabled')
            ->toJson();
    }

    /**
     * Returns a downloadable file with the private key content. This private key can not be
     * downloaded more than once, so after the first try, the key will be deleted.
     *
     * @param Key $key
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPrivateKey(Key $key)
    {
        if (empty($key->private)) {
            abort(ResponseCode::HTTP_NOT_FOUND);
        }

        try {
            // Private key can not be downloaded more than once. After first try, it will be deleted.
            $content = $key->private;
            $key->private = null;
            $key->save();
        } catch (\Throwable $exception) {
            abort(ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        // Starts downloading the key.
        return response()->attachment($content, $key->username . '.key');
    }
}
