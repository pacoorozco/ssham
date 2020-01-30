<?php

namespace Tests\Unit\Libs\RsaSshKey;

use App\Libs\RsaSshKey\InvalidInputException;
use App\Libs\RsaSshKey\RsaSshKey;
use App\Rules\ValidRSAPrivateKeyRule;
use PHPUnit\Framework\TestCase;


class RsaSshKeyTest extends TestCase
{
    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';
    const FINGERPRINT_VALID_PUBLIC_KEY = 'b8:21:f0:51:4a:02:0a:ff:a8:c3:1b:7b:ae:03:02:a9';

    const INVALID_PUBLIC_KEY = 'ssh-rsa-invalid-public-key';

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

    /**
     * Asserts that two RSA keys are equal.
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     */
    private function assertEqualRsaKeys(string $expected, string $actual, string $message = ''): void
    {
        $this->assertTrue(RsaSshKey::compareKeys($expected, $actual), $message);
    }

    public function test_create_returns_public_and_private_keys()
    {
        $key = RsaSshKey::create();
        $this->assertArrayHasKey('privatekey', $key, 'Array has not "privatekey" key.');
        $this->assertNotEmpty($key['privatekey']);

        $this->assertArrayHasKey('publickey', $key, 'Array has not "publickey" key.');
        $this->assertNotEmpty($key['publickey']);
    }

    public function test_getPublicKey_returns_public_key()
    {
        $this->assertEqualRsaKeys(
            self::VALID_PUBLIC_KEY,
            RsaSshKey::getPublicKey(self::VALID_PUBLIC_KEY)
        );
    }

    public function test_getPublicKey_throws_exception_with_invalid_input()
    {
        $this->expectException(InvalidInputException::class);
        RsaSshKey::getPublicKey(self::INVALID_PUBLIC_KEY);
    }

    public function test_getPrivateKey_returns_private_key()
    {
        $this->assertEqualRsaKeys(
            self::VALID_PRIVATE_KEY,
            RsaSshKey::getPrivateKey(self::VALID_PRIVATE_KEY)
        );
    }

    public function test_getPrivateKey_throws_exception_with_invalid_input()
    {
        $this->expectException(InvalidInputException::class);
        RsaSshKey::getPrivateKey(self::INVALID_PRIVATE_KEY);
    }

    public function test_getPublicFingerprint_returns_fingerprint()
    {
        $this->assertEquals(
            self::FINGERPRINT_VALID_PUBLIC_KEY,
            RsaSshKey::getPublicFingerprint(self::VALID_PUBLIC_KEY)
        );
    }

    public function test_getPublicFingerprint_throws_exception_with_invalid_input()
    {
        $this->expectException(InvalidInputException::class);
        RsaSshKey::getPublicFingerprint(self::INVALID_PUBLIC_KEY);
    }
}
