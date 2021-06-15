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

namespace App\Providers;

use App\Models\ControlRule;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Policies\ControlRulePolicy;
use App\Policies\HostgroupPolicy;
use App\Policies\HostPolicy;
use App\Policies\KeygroupPolicy;
use App\Policies\KeyPolicy;
use App\Policies\PersonalAccessTokenPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Host::class => HostPolicy::class,
        Hostgroup::class => HostgroupPolicy::class,
        Key::class => KeyPolicy::class,
        Keygroup::class => KeygroupPolicy::class,
        ControlRule::class => ControlRulePolicy::class,
        User::class => UserPolicy::class,
        PersonalAccessToken::class, PersonalAccessTokenPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
