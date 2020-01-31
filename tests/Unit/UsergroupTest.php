<?php

namespace Tests\Unit;

use App\Usergroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\ModelTestCase;

class UsergroupTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
    {
        $m = new Usergroup();
        $this->assertEquals([
            'name',
            'description',
        ], $m->getFillable());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new Usergroup();
        $this->assertEquals([
            'id' => 'int',
            'name' => 'string',
            'description' => 'string',
        ], $m->getCasts());
    }

    public function test_users_relation()
    {
        $m = new Usergroup();
        $r = $m->users();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    public function test_hostgroups_relation()
    {
        $m = new Usergroup();
        $r = $m->hostgroups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

}
