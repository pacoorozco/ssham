<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SSHAM\Http\Requests\SettingsRequest;
use Torann\Registry\Facades\Registry;


class SettingsController extends Controller
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
     * @return View
     */
    public function index()
    {
        $settings = Registry::all();

        return view('settings.index', compact('settings'));
    }

    /**
     * @param SettingsRequest $request
     * @return RedirectResponse
     */
    public function update(SettingsRequest $request)
    {
        Registry::store($request->except('_token', '_method'));

        flash()->success(trans('settings/messages.save.success'));

        return redirect()->route('settings.index');
    }

}
