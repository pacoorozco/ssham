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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2020 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = setting()->all();

        return view('settings.index', compact('settings'));
    }

    /**
     * @param SettingsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingsRequest $request)
    {
        setting()->set([
            'authorized_keys' => $request->authorized_keys,
            'private_key' => $request->private_key,
            'public_key' => $request->public_key,
            'temp_dir' => $request->temp_dir,
            'ssh_timeout' => $request->ssh_timeout,
            'ssh_port' => $request->ssh_port,
            'mixed_mode' => $request->mixed_mode,
            'ssham_file' => $request->ssham_file,
            'non_ssham_file' => $request->non_ssham_file,
            'cmd_remote_updater' => $request->cmd_remote_updater,
        ]);

        return redirect()->route('settings.index')
            ->withSuccess(__('settings/messages.save.success'));
    }
}
