<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HostStatus extends Enum
{
    const INITIAL_STATUS         = 'INITIAL';
    const AUTH_FAIL_STATUS       = 'AUTHFAIL';
    const GENERIC_FAIL_STATUS    = 'GENERICFAIL';
    const SUCCESS_STATUS         = 'SUCCESS';
}
