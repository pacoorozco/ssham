<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\ControlRule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ControlRulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::ViewRules->value, ControlRule::class);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::EditRules->value, ControlRule::class);
    }

    public function delete(User $user, ControlRule $rule): bool
    {
        return $user->can(Permissions::DeleteRules->value, $rule);
    }
}
