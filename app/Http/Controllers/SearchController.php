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

use App\Http\Requests\SearchRequest;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use Illuminate\View\View;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): View
    {
        $searchString = $request->searchString();

        if (is_null($searchString)) {
            return view('search.index');
        }

        $searchResults = (new Search())
            ->registerModel(Key::class, function (ModelSearchAspect $modelSearchAspect) {
                $modelSearchAspect
                    ->addSearchableAttribute('username') // return results for partial matches on usernames
                    ->addExactSearchableAttribute('fingerprint'); // only return results that exactly match the fingerprint
            })
            ->registerModel(Keygroup::class, 'name', 'description')
            ->registerModel(Host::class, 'hostname')
            ->registerModel(Hostgroup::class, 'name', 'description')
            ->perform($searchString);

        return view('search.results')
            ->with('searchString', $searchString)
            ->with('searchResults', $searchResults);
    }
}
