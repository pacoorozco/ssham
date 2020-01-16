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

namespace App;

use Zizaco\Entrust\EntrustPermission;

/**
 * Class Permission
 *
 * The Permission model has the same three attributes as the Role:
 *   - name — Unique name for the permission, used for looking up permission information in the application layer. For
 *   example: "create-post", "edit-user", "post-payment", "mailing-list-subscribe".
 *   - display_name — Human readable name for the permission. Not necessarily unique and optional. For example "Create
 *   Posts", "Edit Users", "Post Payments",  "Subscribe to mailing list".
 *   - description — A more detailed explanation of the Permission. In general, it may be helpful to think of the last
 *   two attributes in the form of a sentence: "The permission display_name allows a user to description."
 *
 * @package App
 */
class Permission extends EntrustPermission
{
}
