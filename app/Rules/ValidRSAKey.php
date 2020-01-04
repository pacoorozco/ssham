<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use phpseclib\Crypt\RSA;

class ValidRSAPublicKey implements Rule
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
        $rsa->setPublicKey();
        $convertedKey = $rsa->getPublicKey(RSA::PUBLIC_FORMAT_OPENSSH);

        return ($value === $convertedKey);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid RSA public key.';
    }
}
