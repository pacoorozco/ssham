<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Roles extends Enum
{
    const SuperAdmin = 'super-admin';
    const Admin = 'admin';
    const Operator = 'operator';
    const Auditor = 'auditor';
}
