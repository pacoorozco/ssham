<?php

namespace App\Jobs;

use App\Models\Hostgroup;
use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteHostgroup
{
    use Dispatchable;

    private Hostgroup $group;

    public function __construct(Hostgroup $group)
    {
        $this->group = $group;
    }

    public function handle(): bool
    {
        // delete() can return null while we want to return boolean.
        return $this->group->delete() ?? false;
    }
}
