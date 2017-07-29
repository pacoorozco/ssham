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

namespace SSHAM\Jobs;

use Illuminate\Bus\Queueable;

abstract class Job
{
    /*
      |--------------------------------------------------------------------------
      | Queueable Jobs
      |--------------------------------------------------------------------------
      |
      | This job base class provides a central location to place any logic that
      | is shared across all of your jobs. The trait included with the class
      | provides access to the "queueOn" and "delay" queue helper methods.
      |
     */

use Queueable;
}
