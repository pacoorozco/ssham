<?php

namespace App\Exceptions;

use Exception;

class PusherException extends Exception
{
    public function report(): bool
    {
        // We don't want to report these kind of exceptions, so we should return true.
        // @see https://laravel.com/docs/9.x/errors#renderable-exceptions

        return true;
    }
}
