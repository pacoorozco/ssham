<?php

namespace App\Rules;

use App\Libs\RsaSshKey\InvalidInputException;
use App\Libs\RsaSshKey\RsaSshKey;
use Illuminate\Contracts\Validation\Rule;

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
        try {
            $key = RsaSshKey::getPublicKey($value);
        } catch (InvalidInputException $exception) {
            return false;
        }

        return ($value === $key);
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
