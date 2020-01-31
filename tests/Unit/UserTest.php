<?php

namespace Tests\Unit;

use App\Libs\RsaSshKey\RsaSshKey;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';
    const FINGERPRINT_VALID_PUBLIC_KEY = 'b8:21:f0:51:4a:02:0a:ff:a8:c3:1b:7b:ae:03:02:a9';

    const INVALID_PUBLIC_KEY = 'ssh-rsa-invalid-public-key';

    public function test_contains_valid_fillable_properties()
    {
        $m = new User();
        $this->assertEquals([
            'username',
            'email',
            'password',
            'enabled',
        ], $m->getFillable());
    }

    public function test_contains_valid_hidden_properties()
    {
        $m = new User();
        $this->assertEquals([
            'password',
            'remember_token',
            'auth_type',
        ], $m->getHidden());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new User();
        $this->assertEquals([
            'id' => 'int',
            'username' => 'string',
            'email' => 'string',
            'password' => 'string',
            'public_key' => 'string',
            'fingerprint' => 'string',
            'auth_type' => 'string',
            'enabled' => 'boolean',
            'email_verified_at' => 'datetime',
        ], $m->getCasts());
    }

    public function test_usergroups_relation()
    {
        $m = new User();
        $r = $m->usergroups();
        $this->assertInstanceOf(BelongsToMany::class, $r);
    }

    public function test_fingerprint_is_set_when_public_key_is_attached()
    {
        $m = new User();

        $this->assertTrue($m->attachPublicKey(self::VALID_PUBLIC_KEY, true));
        $this->assertTrue(RsaSshKey::compareKeys(self::VALID_PUBLIC_KEY, $m->public_key));
        $this->assertEquals(self::FINGERPRINT_VALID_PUBLIC_KEY, $m->fingerprint);
    }

    public function test_attachPublicKey_result_with_invalid_input() {
        $m = new User();

        $this->assertFalse($m->attachPublicKey(self::INVALID_PUBLIC_KEY, true));
        $this->assertNull($m->public_key);
        $this->assertNull($m->fingerprint);
    }

    public function test_username_is_lowercase()
    {
        $m = new User();

        $test_data = [
            'User' => 'user',
            'ADMIN' => 'admin',
            'user' => 'user',
            'admin' => 'admin',
        ];

        foreach ($test_data as $input => $want) {
            $m->username = $input;
            $this->assertEquals($want, $m->getAttribute('username'));
        }
    }
}
