<?php

namespace App\Jobs;

use App\Models\Keygroup;
use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteKeygroup
{
    use Dispatchable;

    private Keygroup $group;

    public function __construct(Keygroup $group)
    {
        $this->group = $group;
    }

    public function handle(): bool
    {
        // delete() can return null while we want to return boolean.
        return $this->group->delete() ?? false;
    }
}
