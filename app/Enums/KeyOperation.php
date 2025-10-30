<?php

namespace App\Enums;

enum KeyOperation: string
{
    case CREATE_OPERATION = 'create';
    case IMPORT_OPERATION = 'import';
    case NOOP_OPERATION = 'maintain';
}
