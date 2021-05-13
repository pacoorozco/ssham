<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Unit;

use App\Libs\RsaSshKey\RsaSshKey;
use App\Models\Key;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\ModelTestCase;

class KeyTest extends ModelTestCase
{
    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';
    const FINGERPRINT_VALID_PUBLIC_KEY = 'b8:21:f0:51:4a:02:0a:ff:a8:c3:1b:7b:ae:03:02:a9';

    const INVALID_PUBLIC_KEY = 'ssh-rsa-invalid-public-key';

    public function test_contains_valid_fillable_properties()
    {
        $m = new Key();
        $this->assertEquals([
            'username',
            'enabled',
        ], $m->getFillable());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new Key();
        $this->assertEquals([
            'enabled' => 'boolean',
        ], $m->getCasts());
    }

    public function test_groups_relation()
    {
        $m = new Key();
        $r = $m->groups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    public function test_public_key_is_set_when_public_key_is_attached()
    {
        $m = new Key();

        try {
            $m->attachKey(self::VALID_PUBLIC_KEY);
        } catch (\Throwable $exception) {
            $this->assertNull($exception);
        }
        $this->assertTrue(RsaSshKey::compareKeys(self::VALID_PUBLIC_KEY, $m->public));
    }

    public function test_fingerprint_is_set_when_public_key_is_attached()
    {
        $m = new Key();

        try {
            $m->attachKey(self::VALID_PUBLIC_KEY);
        } catch (\Throwable $exception) {
            $this->assertNull($exception);
        }
        $this->assertEquals(self::FINGERPRINT_VALID_PUBLIC_KEY, $m->fingerprint);
    }

    public function test_attachPublicKey_result_with_invalid_input()
    {
        $m = new Key();

        $this->expectException(\Exception::class);
        $m->attachKey(self::INVALID_PUBLIC_KEY);

        $this->assertNull($m->public);
        $this->assertNull($m->fingerprint);
    }
}
