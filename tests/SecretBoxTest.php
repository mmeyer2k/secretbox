<?php

declare(strict_types=1);

use Mmeyer2k\SecretBox\SecretBox;
use PHPUnit\Framework\TestCase;

final class SecretBoxTest extends TestCase
{
    public function testBasicEncryption(): void
    {
        $msg = 'Hello World!';
        $key = random_bytes(32);

        $enc = SecretBox::encrypt($msg, $key);
        $dec = SecretBox::decrypt($enc, $key);

        $this->assertSame($dec, $msg);
    }

    public function testBadChecksum(): void
    {
        $key = random_bytes(32);

        $this->expectException(SodiumException::class);

        SecretBox::decrypt('a bunch of garbage', $key);
    }

    public function testBadKey(): void
    {
        $this->expectException(SodiumException::class);

        SecretBox::encrypt('asdf', 'bad key');
    }

    public function testKeyRotation(): void
    {
        $key1 = random_bytes(32);
        $key2 = random_bytes(32);
        $key3 = random_bytes(32);

        $keys = [$key1, $key2, $key3];

        $msg = 'Hello World!';
        $enc = SecretBox::encrypt($msg, $key1);

        $idx = null;
        $dec = SecretBox::decrypt($enc, $keys, $idx);
        $this->assertEquals($msg, $dec);
        $this->assertEquals(0, $idx);

        $idx = null;
        $dec = SecretBox::decrypt($enc, $keys, $idx);
        $this->assertEquals($msg, $dec);
        $this->assertEquals(1, $idx);

        $idx = null;
        $dec = SecretBox::decrypt($enc, $keys, $idx);
        $this->assertEquals($msg, $dec);
        $this->assertEquals(2, $idx);
    }
}
