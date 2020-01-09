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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2020 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use phpseclib\Crypt\RSA;

class ValidRSAPrivateKey implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $rsa = new RSA();
        $rsa->loadKey($value);
        $convertedKey = $rsa->getPrivateKey();

        return ($value === $convertedKey);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid RSA private key.';
    }
}
