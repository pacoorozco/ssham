<?php

namespace Tests\Unit;

use App\Rule;
use Tests\ModelTestCase;

class RuleTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
    {
        $m = new Rule();
        $this->assertEquals([
            'keygroup_id',
            'hostgroup_id',
            'action',
            'name',
            'enabled',
        ], $m->getFillable());
    }
}
