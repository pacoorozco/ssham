<?php

namespace Tests\Unit\Models;

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use Tests\ModelTestCase;

class ControlRuleTest extends ModelTestCase
{
    /** @test */
    public function contains_valid_fillable_properties(): void
    {
        $m = new ControlRule();
        $this->assertEquals([
            'source_id',
            'target_id',
            'action',
            'name',
            'enabled',
        ], $m->getFillable());
    }

    /** @test */
    public function contains_valid_casts_properties(): void
    {
        $m = new ControlRule();
        $this->assertEquals([
            'id'     => 'int',
            'action' => ControlRuleAction::class,
        ], $m->getCasts());
    }
}
