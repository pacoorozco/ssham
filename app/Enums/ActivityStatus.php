<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ActivityStatus extends Enum implements LocalizedEnum
{
    const Success = 'STATUS_SUCCESS';
    const Failure = 'STATUS_FAIL';

    // Used on presenter if the value on the database is different from the ones above.
    // e.g. Old messages with values that doesn't exist anymore.
    const Unknown = 'STATUS_UNKNOWN';
}
