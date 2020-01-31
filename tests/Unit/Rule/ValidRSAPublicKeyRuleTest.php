<?php

namespace Tests\Unit\Rule;

use App\Rules\ValidRSAPublicKeyRule;
use Tests\TestCase;

class ValidRSAPublicKeyRuleTest extends TestCase
{
    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';
    const INVALID_PUBLIC_KEY = 'ssh-rsa-invalid-public-key';

    /**
     * @var \App\Rules\ValidRSAPublicKeyRule
     */
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new ValidRSAPublicKeyRule();
    }

    public function test_valid_rsa_public_key_passes()
    {
        $this->assertTrue($this->rule->passes('key', self::VALID_PUBLIC_KEY));
    }

    public function test_invalid_rsa_public_key_not_passes()
    {
        $this->assertFalse($this->rule->passes('key', self::INVALID_PUBLIC_KEY));
    }
}
