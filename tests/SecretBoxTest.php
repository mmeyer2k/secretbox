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
        $key0 = random_bytes(32);
        $key1 = random_bytes(32);
        $key2 = random_bytes(32);

        $keys = [$key0, $key1, $key2];

        $msg = 'Hello World!';

        $enc = SecretBox::encrypt($msg, $key0);
        $dec = SecretBox::decrypt($enc, $keys);
        $this->assertEquals($msg, $dec);

        $enc = SecretBox::encrypt($msg, $key1);
        $dec = SecretBox::decrypt($enc, $keys);
        $this->assertEquals($msg, $dec);

        $enc = SecretBox::encrypt($msg, $key2);
        $dec = SecretBox::decrypt($enc, $keys);
        $this->assertEquals($msg, $dec);
    }

    public function testVector()
    {
        $msg = 'Hello World!';
        $key = str_repeat(chr(0), 32);
        $vector = base64_decode('YC6q3zfHDmvwkpn1cVprKn2zcEBQDECzM10HlhPnpLRbMc5q/yZsKUX5RzZ3oyvOeKmVsA==');
        $dec = SecretBox::decrypt($vector, $key);
        $this->assertEquals($msg, $dec);
    }
}
