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

namespace App\Providers;

use App\Models\ControlRule;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Observers\ControlRuleObserver;
use App\Observers\HostgroupObserver;
use App\Observers\HostObserver;
use App\Observers\KeygroupObserver;
use App\Observers\KeyObserver;
use App\Observers\PersonalAccessTokenObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

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
        // Observers
        Host::observe(HostObserver::class);
        Hostgroup::observe(HostgroupObserver::class);
        Key::observe(KeyObserver::class);
        Keygroup::observe(KeygroupObserver::class);
        User::observe(UserObserver::class);
        PersonalAccessToken::observe(PersonalAccessTokenObserver::class);
        ControlRule::observe(ControlRuleObserver::class);

        // Use Sanctum with a custom model
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
