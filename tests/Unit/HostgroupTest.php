<?php

namespace Tests\Unit;

use App\Hostgroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

class HostgroupTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
    {
        $m = new Hostgroup();
        $this->assertEquals([
            'name',
            'description',
        ], $m->getFillable());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new Hostgroup();
        $this->assertEquals([
            'id' => 'int',
            'name' => 'string',
            'description' => 'string',
        ], $m->getCasts());
    }

    public function test_hosts_relation()
    {
        $m = new Hostgroup();
        $r = $m->hosts();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }
}
