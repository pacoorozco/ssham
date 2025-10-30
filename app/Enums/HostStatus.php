<?php

namespace App\Enums;

enum HostStatus: string
{
    case INITIAL_STATUS = 'INITIAL';
    case AUTH_FAIL_STATUS = 'AUTHFAIL';
    case GENERIC_FAIL_STATUS = 'GENERICFAIL';
    case SUCCESS_STATUS = 'SUCCESS';
}
