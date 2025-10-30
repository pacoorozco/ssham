<?php

namespace App\Enums;

enum Roles: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Operator = 'operator';
    case Auditor = 'auditor';

    public function label(): string
    {
        return match ($this) {
            Roles::SuperAdmin => 'Super Admin',
            Roles::Admin => 'Admin',
            Roles::Operator => 'Operator',
            Roles::Auditor => 'Auditor',
        };
    }
}
