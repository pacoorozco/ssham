<?php

namespace App\Enums;

enum Permissions: string
{
    case ViewKeys = 'view keys';
    case EditKeys = 'edit keys';
    case DeleteKeys = 'delete keys';
    case ViewHosts = 'view hosts';
    case EditHosts = 'edit hosts';
    case DeleteHosts = 'delete hosts';
    case ViewRules = 'view rules';
    case EditRules = 'edit rules';
    case DeleteRules = 'delete rules';
    case ViewUsers = 'view users';
    case EditUsers = 'edit users';
    case DeleteUsers = 'delete users';
    case EditSettings = 'edit settings';
}
