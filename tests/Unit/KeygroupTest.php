<?php

namespace Tests\Unit;

use App\Keygroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

class KeygroupTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
    {
        $m = new Keygroup();
        $this->assertEquals([
            'name',
            'description',
        ], $m->getFillable());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new Keygroup();
        $this->assertEquals([
            'id' => 'int',
            'name' => 'string',
            'description' => 'string',
        ], $m->getCasts());
    }

    public function test_users_relation()
    {
        $m = new Keygroup();
        $r = $m->keys();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }
}
