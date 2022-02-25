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

use App\Models\Activity;
use App\Models\ControlRule;
use App\Models\Host;
use App\Models\Key;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $key_count = Key::count();
        $host_count = Host::count();
        $rule_count = ControlRule::count();
        $user_count = User::count();

        $activities = Activity::all()->sortByDesc('created_at')->take(15);

        return view('dashboard.index')
            ->with('key_count', $key_count)
            ->with('host_count', $host_count)
            ->with('rule_count', $rule_count)
            ->with('user_count', $user_count)
            ->with('activities', $activities);
    }
}
