<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class KeyOperation extends Enum
{
    const CREATE_OPERATION = 'create';
    const IMPORT_OPERATION = 'import';
    const NOOP_OPERATION = 'maintain';
}
