<?php

namespace SSHAM\Providers;

use Illuminate\Support\ServiceProvider;

class OpenSSHKeyValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registering the validator extension with the validator factory
        \Validator::extend('openssh_key', function ($attribute, $value, $parameters) {

            // Convert $value in a OpenSSH Key and compares with input
            $rsa = new \Crypt_RSA();
            $rsa->loadKey($value);
            $rsa->setPublicKey();

            return ($value == $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH));
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
