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

namespace Tests\Unit\Rule;

use App\Rules\ValidRSAPrivateKeyRule;
use Tests\TestCase;

class ValidRSAPrivateKeyRuleTest extends TestCase
{
    const VALID_PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
    MIICXQIBAAKBgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZT
    SSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanO
    ABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQIDAQAB
    AoGAWp368OMphl3lipBD6v4q4WIGtbjYG/sJsryAN/Ayef4tona5YmsIeSr1t66s
    iq/YJnxcL+/xgobsePQbwVdWf2Di+Qnnwj6VjaRHb/YlFw+mA4EuEeHbSpMEGDqW
    T7dfoa1nbKMlYVojndLYRin17nU2QAmq9TOgi+C06FcMAAECQQDzEiHBnwnT5Ax9
    tRM+1zOv76OfsWtsjSH2tn8OhUkzHDapssZJV5T/HhwEg8rDw9ShUS7AHbG3RWip
    N6lQBLgBAkEA8izjffQR9QFQ/gBrh40nQshtfrhK8lk+5jjIh8K81ZeqLqvnOGq4
    LyxMVO7Q+CcVuEJp0qmL+Fcnn96O/+nBIQJBAJ0kjsA/Ujozh8PJSdzpgdfvREgc
    ioeOInP+fdvkXXN2fPxuwHRv87qPO6vLjE3Nj+yOsHuxdtA2RjiH7KT3uAECQQC/
    o5/+KucO35TM+14cLSn1Yg+rqIC+WLs6iZK+Q+8UgukL98KIVYMc6UwaJcW9qYg5
    gGynZL27rpRPoVm9z6ehAkB5H13ugPHezsZIaeF72CmiJ23x4ViECz9oQno9vcO3
    U+6N7Rn2R1Gt/UaoiQB3ziO0O+nKDyYXpErFE60VA0k/
    -----END RSA PRIVATE KEY-----';

    const INVALID_PRIVATE_KEY = 'ssh-rsa-invalid-private-key';

    protected ValidRSAPrivateKeyRule $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new ValidRSAPrivateKeyRule();
    }

    /** @test */
    public function a_valid_rsa_private_key_should_pass(): void
    {
        $this->assertTrue($this->rule->passes('key', self::VALID_PRIVATE_KEY));
    }

    /** @test */
    public function an_invalid_rsa_private_key_should_fail(): void
    {
        $this->assertFalse($this->rule->passes('key', self::INVALID_PRIVATE_KEY));
    }
}
