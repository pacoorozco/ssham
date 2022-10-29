<?php

namespace Tests\Feature\Models;

use App\Models\Key;
use Illuminate\Support\Str;
use PacoOrozco\OpenSSH\PublicKey;
use Tests\Feature\TestCase;

class KeyTest extends TestCase
{
    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';

    /** @test */
    public function fingerprint_should_be_set_when_key_is_created(): void
    {
        /** @var Key $key */
        $key = Key::factory()->create([
            'public' => self::VALID_PUBLIC_KEY,
        ]);
        $wantFingerprint = PublicKey::fromString(self::VALID_PUBLIC_KEY)->getFingerPrint('md5');

        $this->assertDatabaseHas('keys', ['id' => $key->id, 'fingerprint' => $wantFingerprint]);
    }

    /** @test */
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

    /** @test */
    public function key_uses_uuid_as_primary_key(): void
    {
        /** @var Key $key */
        $key = Key::factory()->create();

        $this->assertTrue(Str::isUuid($key->id));
        $this->assertTrue(Str::isUuid($key->getKey()));
    }
}
