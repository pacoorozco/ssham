<?php

namespace App\Jobs;

use App\Models\ControlRule;
use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteControlRule
{
    use Dispatchable;

    private ControlRule $rule;

    public function __construct(ControlRule $rule)
    {
        $this->rule = $rule;
    }

    public function handle(): bool
    {
        // delete() can return null while we want to return boolean.
        return $this->rule->delete() ?? false;
    }
}
