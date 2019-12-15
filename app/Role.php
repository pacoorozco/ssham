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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2019 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App;

use Zizaco\Entrust\EntrustRole;

/**
 * Class Role
 *
 * The Role model has three main attributes:
 *   - name — Unique name for the Role, used for looking up role information in the application layer. For example:
 *   "admin", "owner", "employee".
 *   - display_name — Human readable name for the Role. Not necessarily unique and optional. For example: "User
 *   Administrator", "Project Owner", "Widget Co. Employee".
 *   - description — A more detailed explanation of what the Role does. Also optional.
 *
 * Both display_name and description are optional; their fields are nullable in the database.
 *
 * @package App
 */
class Role extends EntrustRole
{
}
