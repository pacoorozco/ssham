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

use SSHAM\Host;
use SSHAM\Hostgroup;
use SSHAM\User;
use SSHAM\Usergroup;

class DashboardController extends Controller
{

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
        $data = [];
        $data['users'] = User::all()->count();
        $data['user_groups'] = Usergroup::all()->count();
        $data['hosts'] = Host::all()->count();
        $data['host_groups'] = Hostgroup::all()->count();

        return view('dashboard.index')
            ->with('data', $data);
    }
}