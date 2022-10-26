<?php

namespace Tests\Feature\Presenters;

use App\Enums\ControlRuleAction;
use App\Models\ControlRule;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ModelTestCase;

class ControlRulePresenterTest extends ModelTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider providesActionValues
     */
    public function it_should_return_the_proper_icon(
        string $action,
        string $want,
    ): void {
        $m = ControlRule::factory()->make([
            'action' => $action,
        ]);

        $this->assertStringContainsString($want, $m->present()->actionWithIcon());
    }

    public function providesActionValues(): Generator
    {
        yield 'allow action' => [
            'action' => ControlRuleAction::Allow,
            'want'   => '<i class="fa fa-lock-open"></i>',
        ];

        yield 'deny action' => [
            'action' => ControlRuleAction::Deny,
            'want'   => '<i class="fa fa-lock"></i>',
        ];
    }
}
