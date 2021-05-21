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

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UsernameRule implements Rule
{
    // Based on 'The Open Group Base Specifications Issue 7, 2018 edition'.
    // https://pubs.opengroup.org/onlinepubs/9699919799/basedefs/V1_chap03.html#tag_03_437
    const VALID_USERNAME_REGEXP = '/^[A-Za-z0-9][A-Za-z0-9._-]*$/';

    public function passes($attribute, $value): bool
    {
        return (1 == preg_match(self::VALID_USERNAME_REGEXP, $value));
    }

    public function message(): string
    {
        return 'The :attribute is not a valid POSIX username.';
    }
}
