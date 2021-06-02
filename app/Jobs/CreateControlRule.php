<?php

namespace App\Jobs;

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateControlRule
{
    use Dispatchable;

    private string $name;

    private int $source_id;

    private int $target_id;

    private ControlRuleAction $action;

    public function __construct(string $name, int $source_id, int $target_id, ControlRuleAction $action)
    {
        $this->name = $name;
        $this->source_id = $source_id;
        $this->target_id = $target_id;
        $this->action = $action;
    }

    public function handle(): ControlRule
    {
        return ControlRule::create([
            'name' => $this->name,
            'source_id' => $this->source_id,
            'target_id' => $this->target_id,
            'action' => $this->action,
        ]);
    }
}
