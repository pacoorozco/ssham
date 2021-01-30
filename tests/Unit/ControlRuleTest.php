<?php

namespace Tests\Unit;

use App\Models\ControlRule;
use Tests\ModelTestCase;

class ControlRuleTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
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
}
