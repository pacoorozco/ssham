<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Observers;

use App\Models\PersonalAccessToken;

class PersonalAccessTokenObserver
{
    /**
     * Handle the PersonalAccessToken "created" event.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return void
     */
    public function created(PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "updated" event.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return void
     */
    public function updated(PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "deleted" event.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return void
     */
    public function deleted(PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "restored" event.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return void
     */
    public function restored(PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Handle the PersonalAccessToken "force deleted" event.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return void
     */
    public function forceDeleted(PersonalAccessToken $personalAccessToken)
    {
        //
    }
}
