<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CREATE_OPERATION()
 * @method static static IMPORT_OPERATION()
 * @method static static NOOP_OPERATION()
 */
final class KeyOperation extends Enum
{
    const CREATE_OPERATION = 'create';
    const IMPORT_OPERATION = 'import';
    const NOOP_OPERATION = 'maintain';
}
