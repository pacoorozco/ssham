<?php

namespace Tests\Feature\Models;

use App\Models\Key;
use Illuminate\Support\Str;
use PacoOrozco\OpenSSH\PublicKey;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\TestCase;

final class KeyTest extends TestCase
{
    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';

    #[Test]
    public function fingerprint_should_be_set_when_key_is_created(): void
    {
        /** @var Key $key */
        $key = Key::factory()->create([
            'public' => self::VALID_PUBLIC_KEY,
        ]);
        $wantFingerprint = PublicKey::fromString(self::VALID_PUBLIC_KEY)->getFingerPrint('md5');

        $this->assertDatabaseHas('keys', ['id' => $key->id, 'fingerprint' => $wantFingerprint]);
    }

    #[Test]
    public function fingerprint_should_be_set_when_key_is_updated(): void
    {
        /** @var Key $key */
        $key = Key::factory()->create();
        $key->update([
            'public' => self::VALID_PUBLIC_KEY,
        ]);
        $wantFingerprint = PublicKey::fromString(self::VALID_PUBLIC_KEY)->getFingerPrint('md5');

        $this->assertDatabaseHas('keys', ['id' => $key->id, 'fingerprint' => $wantFingerprint]);
    }

    #[Test]
    public function key_uses_uuid_as_primary_key(): void
    {
        /** @var Key $key */
        $key = Key::factory()->create();

        $this->assertTrue(Str::isUuid($key->id));
        $this->assertTrue(Str::isUuid($key->getKey()));
    }

    #[Test]
    public function it_should_return_a_formatted_key_comment(): void
    {
        /** @var Key $key */
        $key = Key::factory()->create([
            'name' => 'foo bar key',
            'public' => self::VALID_PUBLIC_KEY,
        ]);

        // Get the last part of the public key, which is the key's comment.
        $keyComment = explode(' ', $key->public, 3)[2];

        $this->assertEquals('SSHAM[foo bar key]', $keyComment);
    }
}
