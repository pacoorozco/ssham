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

namespace SSHAM\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Crypt_RSA;

class RSAKeyValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registering the validator extension with the validator factory
        Validator::extend('rsa_key', function ($attribute, $value, $parameters) {

            // first item equals key type (public or private)
            $keyType = array_shift($parameters);

            $rsa = new Crypt_RSA();
            $rsa->loadKey($value);

            // Convert $value in a RSA Key and compares with input
            if ($keyType == 'private') {
                $convertedKey = $rsa->getPrivateKey();
            } else {
                $rsa->setPublicKey();
                $convertedKey = $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
            }

            return ($value == $convertedKey);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}

