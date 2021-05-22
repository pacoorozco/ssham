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

use App\Enums\ActivityStatus;
use App\Models\PersonalAccessToken;

class PersonalAccessTokenObserver
{
    public function created(PersonalAccessToken $personalAccessToken): void
    {
        $user = $personalAccessToken->relatedUser();

        activity()
            ->performedOn($user)
            ->withProperties(['status' => ActivityStatus::Success])
            ->log(sprintf(
                    "Create personal access token '%s' for '%s'.",
                    $personalAccessToken->name,
                    $user->username)
            );
    }

    public function deleted(PersonalAccessToken $personalAccessToken): void
    {
        $user = $personalAccessToken->relatedUser();

        activity()
            ->performedOn($user)
            ->withProperties(['status' => ActivityStatus::Success])
            ->log(sprintf(
                    "Revoked personal access token '%s' for '%s'.",
                    $personalAccessToken->name,
                    $user->username)
            );
    }
}
