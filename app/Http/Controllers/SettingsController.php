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

use App\Enums\Permissions;
use App\Http\Requests\SettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PacoOrozco\OpenSSH\PrivateKey;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = setting()->all();

        return view('settings.index')
            ->with('settings', $settings);
    }

    public function edit(): View
    {
        $this->authorize(Permissions::EditSettings);

        $settings = setting()->all();

        return view('settings.edit')
            ->with('settings', $settings);
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        $this->authorize(Permissions::EditSettings);

        try {
            $publicKey = PrivateKey::fromString($request->privateKey())->getPublicKey();
        } catch (\Throwable $exception) {
            Log::error('Unable to create public key from the provided private key. error=' . $exception->getMessage());

            return back()
                ->withInput()
                ->withErrors([
                    'private_key' => __('settings/messages.public_key_can_not_be_created')
                ]);
        }

        setting()->set([
            'authorized_keys' => $request->authorizedKeys(),
            'private_key' => $request->privateKey(),
            'public_key' => (string) $publicKey,
            'temp_dir' => $request->tempDir(),
            'ssh_timeout' => $request->sshTimeout(),
            'ssh_port' => $request->sshPort(),
            'mixed_mode' => $request->mixedMode(),
            'ssham_file' => $request->sshamFile(),
            'non_ssham_file' => $request->nonSSHAMFile(),
            'cmd_remote_updater' => $request->cmdRemoteUpdater(),
        ]);

        return redirect()->route('settings.index')
            ->withSuccess(__('settings/messages.save.success'));
    }
}
