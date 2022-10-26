<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INITIAL_STATUS()
 * @method static static AUTH_FAIL_STATUS()
 * @method static static GENERIC_FAIL_STATUS()
 * @method static static SUCCESS_STATUS()
 */
final class HostStatus extends Enum
{
    const INITIAL_STATUS = 'INITIAL';

    const AUTH_FAIL_STATUS = 'AUTHFAIL';

    const GENERIC_FAIL_STATUS = 'GENERICFAIL';

    const SUCCESS_STATUS = 'SUCCESS';
}
