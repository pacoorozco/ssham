<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class HostStatus extends Enum
{
    const INITIAL_STATUS = 'INITIAL';
    const AUTH_FAIL_STATUS = 'AUTHFAIL';
    const PUBLIC_KEY_FAIL_STATUS = 'KEYAUTHFAIL';
    const GENERIC_FAIL_STATUS = 'GENERICFAIL';
    const SUCCESS_STATUS = 'SUCCESS';
    const HOST_FAIL_STATUS = 'HOSTFAIL';
}
