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

use App\ControlRule;
use App\Host;
use App\Hostgroup;
use App\Http\Requests\SearchRequest;
use App\Key;
use App\Keygroup;
use App\User;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class HomeController extends Controller
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
        $key_count = Key::all()->count();
        $host_count = Host::all()->count();
        $rule_count = ControlRule::all()->count();
        $user_count = User::all()->count();

        return view('home', compact('key_count', 'host_count', 'rule_count', 'user_count'));
    }

    public function search(SearchRequest $request)
    {
        $query = $request->input('query');

        $searchResults = (new Search())
            ->registerModel(Key::class, function (ModelSearchAspect $modelSearchAspect) {
                $modelSearchAspect
                    ->addSearchableAttribute('username') // return results for partial matches on usernames
                    ->addExactSearchableAttribute('fingerprint'); // only return results that exactly match the fingerprint
            })
            ->registerModel(Keygroup::class, 'name', 'description')
            ->registerModel(Host::class, 'hostname')
            ->registerModel(Hostgroup::class, 'name', 'description')
            ->perform($request->input('query'));

        $count = $searchResults->count();

        return view('search', compact('count', 'query', 'searchResults'));
    }
}
