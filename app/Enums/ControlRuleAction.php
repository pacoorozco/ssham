<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Allow()
 * @method static static Deny()
 */
final class ControlRuleAction extends Enum implements LocalizedEnum
{
    const Allow = 'allow';

    const Deny = 'deny';
}
