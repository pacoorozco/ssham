<?php

namespace Tests\Feature\Models;

use App\Models\Key;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PacoOrozco\OpenSSH\PublicKey;
use Tests\TestCase;

class KeyTest extends TestCase
{
    use RefreshDatabase;

    const VALID_PUBLIC_KEY = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key';

    /** @test */
    public function fingerprint_should_be_set_when_key_is_created()
    {
        /** @var Key $key */
        $key = Key::factory()->create([
            'public' => self::VALID_PUBLIC_KEY,
        ]);
        $wantFingerprint = PublicKey::fromString(self::VALID_PUBLIC_KEY)->getFingerPrint();

        $this->assertDatabaseHas('keys', ['id' => $key->id, 'fingerprint' => $wantFingerprint]);
    }

    /** @test */
    public function fingerprint_should_be_set_when_key_is_updated()
    {
        /** @var Key $key */
        $key = Key::factory()->create();
        $key->update([
            'public' => self::VALID_PUBLIC_KEY,
        ]);
        $wantFingerprint = PublicKey::fromString(self::VALID_PUBLIC_KEY)->getFingerPrint();

        $this->assertDatabaseHas('keys', ['id' => $key->id, 'fingerprint' => $wantFingerprint]);
    }
}
