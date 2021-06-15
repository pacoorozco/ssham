<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ViewKeys()
 * @method static static EditKeys()
 * @method static static DeleteKeys()
 * @method static static ViewHosts()
 * @method static static EditHosts()
 * @method static static DeleteHosts()
 * @method static static ViewRules()
 * @method static static EditRules()
 * @method static static DeleteRules()
 * @method static static ViewUsers()
 * @method static static EditUsers()
 * @method static static DeleteUsers()
 */
final class Permissions extends Enum
{
    const ViewKeys   = 'view keys';
    const EditKeys   = 'edit keys';
    const DeleteKeys = 'delete keys';

    const ViewHosts   = 'view hosts';
    const EditHosts   = 'edit hosts';
    const DeleteHosts = 'delete hosts';

    const ViewRules   = 'view rules';
    const EditRules   = 'edit rules';
    const DeleteRules = 'delete rules';

    const ViewUsers   = 'view users';
    const EditUsers   = 'edit users';
    const DeleteUsers = 'delete users';

    const EditSettings = 'edit settings';
}
