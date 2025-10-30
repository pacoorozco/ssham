<?php

namespace App\Enums;

enum ActivityStatus: string
{
    case Success = 'STATUS_SUCCESS';
    case Failure = 'STATUS_FAIL';
    // Used on presenter if the value on the database is different from the ones above.
    // e.g. Old messages with values that doesn't exist anymore.
    case Unknown = 'STATUS_UNKNOWN';

    public function label(): string
    {
        return match ($this) {
            ActivityStatus::Success => 'Success',
            ActivityStatus::Failure => 'Failure',
            ActivityStatus::Unknown => 'Unknown',
        };
    }
}
