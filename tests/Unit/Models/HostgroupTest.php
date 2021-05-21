<?php

namespace Tests\Unit\Models;

use App\Models\Hostgroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

class HostgroupTest extends ModelTestCase
{
    /** @test */
    public function it_contains_valid_fillable_properties(): void
    {
        $m = new Hostgroup();
        $this->assertEquals([
            'name',
            'description',
        ], $m->getFillable());
    }

    /** @test */
    public function it_contains_a_hosts_relation(): void
    {
        $m = new Hostgroup();
        $r = $m->hosts();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    /** @test */
    public function it_contains_a_rules_relation(): void
    {
        $m = new Hostgroup();
        $r = $m->rules();
        $this->assertInstanceOf(HasMany::class, $r);
    }
}
