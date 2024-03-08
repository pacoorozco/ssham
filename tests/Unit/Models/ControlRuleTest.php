<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use App\Models\ControlRule;
use Tests\Unit\ModelTestCase;

class ControlRuleTest extends ModelTestCase
{
    #[Test]
    public function contains_valid_fillable_properties(): void
    {
        $m = new ControlRule();
        $this->assertEquals([
            'source_id',
            'target_id',
            'name',
            'enabled',
        ], $m->getFillable());
    }

    #[Test]
    public function contains_valid_casts_properties(): void
    {
        $m = new ControlRule();
        $this->assertEquals([
            'id' => 'int',
        ], $m->getCasts());
    }
}
