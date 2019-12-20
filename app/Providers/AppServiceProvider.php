<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Providers;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Returns the `active` class when the current route is the active one
        Blade::directive('activeIfInRouteGroup', function (string $expression) {
            if (Route::currentRouteNamed($expression)) {
                return "<?php echo 'active' ?>";
            }
            return "<?php echo '' ?>";
        });

        // Returns the `menu-open` class when the current route is the active one
        Blade::directive('activeMenuIfInRouteGroup', function (string $expression) {
            if (Route::currentRouteNamed($expression)) {
                return "<?php echo 'menu-open' ?>";
            }
            return "<?php echo '' ?>";
        });
    }
}
