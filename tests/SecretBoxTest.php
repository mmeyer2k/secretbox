<?php

declare(strict_types=1);

namespace tests;

use SecretBox;
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

        $this->expectException(Exception::class);

        SecretBox::decrypt('a bunch of garbage', $key);
    }

    public function testBadKey(): void
    {
        $this->expectException(Exception::class);

        (new AesGcm('short key'))->encrypt('Hello World!');
    }

    public function testKeyRotation(): void
    {
        $key1 = random_bytes(32);
        $key2 = random_bytes(32);
        $key3 = random_bytes(32);

        $gcm = new AesGcm($key1);
        $enc = $gcm->encrypt('Hello World!');

        $gcm = new AesGcm($key2, [$key1]);
        $dec = $gcm->decrypt($enc);
        $this->assertEquals('Hello World!', $dec);

        $gcm = new AesGcm($key3, [$key1, $key2]);
        $dec = $gcm->decrypt($enc);
        $this->assertEquals('Hello World!', $dec);
    }
}
