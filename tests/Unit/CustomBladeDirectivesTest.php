<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Tests for custom Blade directives, defined in AppServiceProvider.php.
 *
 * @group Templates
 */
class CustomBladeDirectivesTest extends TestCase
{
    private $blade;

    public function setUp(): void
    {
        parent::setUp();

        $this->blade = resolve('blade.compiler');
    }

    /**
     * @test
     */
    public function activeIfInRouteGroup_should_print_active_when_true()
    {
        Route::shouldReceive('currentRouteNamed')
            ->once()
            ->andReturn(true);

        $this->assertDirectiveOutput(
            'active',
            '@activeIfInRouteGroup($group)',
            ['group' => 'groupname'],
            'Expected to see "active" printed to the screen.'
        );
    }

    /**
     * @test
     */
    public function activeIfInRouteGroup_should_print_nothing_when_false()
    {
        Route::shouldReceive('currentRouteNamed')
            ->once()
            ->andReturn(false);

        $this->assertDirectiveOutput(
            '',
            '@activeIfInRouteGroup($group)',
            ['group' => 'groupname'],
            'Expected to not see anything printed.'
        );
    }

    /**
     * @test
     */
    public function activeMenuIfInRouteGroup_should_print_active_when_true()
    {
        Route::shouldReceive('currentRouteNamed')
            ->once()
            ->andReturn(true);

        $this->assertDirectiveOutput(
            'menu-open',
            '@activeMenuIfInRouteGroup($group)',
            ['group' => 'groupname'],
            'Expected to see "menu-open" printed to the screen.'
        );
    }

    /**
     * @test
     */
    public function activeMenuIfInRouteGroup_should_print_nothing_when_false()
    {
        Route::shouldReceive('currentRouteNamed')
            ->once()
            ->andReturn(false);

        $this->assertDirectiveOutput(
            '',
            '@activeMenuIfInRouteGroup($group)',
            ['group' => 'groupname'],
            'Expected to not see anything printed.'
        );
    }

    /**
     * Evaluate a Blade expression with the given $variables in scope.
     *
     * @param string $expected   The expected output.
     * @param string $expression The Blade directive, as it would be written in a view.
     * @param array  $variables  Variables to extract() into the scope of the eval() statement.
     * @param string $message    A message to display if the output does not match $expected.
     */
    protected function assertDirectiveOutput(
        string $expected,
        string $expression = '',
        array $variables = [],
        string $message = ''
    ) {
        $compiled = $this->blade->compileString($expression);

        /*
         * Normally using eval() would be a big no-no, but when you're working on a templating
         * engine it's difficult to avoid.
         */
        ob_start();
        extract($variables);
        eval(' ?>' . $compiled . '<?php ');
        $output = ob_get_clean();

        $this->assertEquals($expected, $output, $message);
    }
}
