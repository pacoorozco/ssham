<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Local()
 * @method static static External()
 * @method static static Unknown()
 */
final class AuthType extends Enum
{
    const Local = 'local';
    const External = 'external';
}
