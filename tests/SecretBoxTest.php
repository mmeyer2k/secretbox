<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SecretBoxTest extends TestCase
{
    public function testBasicEncryption(): void
    {
        $msg = 'Hello World!';
        $key = random_bytes(32);

        $enc = SecretBox::encrypt($msg, $key);
        $dec = SecretBox::decrypt($enc, [$key]);

        $this->assertSame($dec, $msg);
    }

    public function testBadChecksum(): void
    {
        $key = random_bytes(32);

        $this->expectException(Exception::class);

        SecretBox::decrypt('a bunch of garbage', [$key]);
    }

    public function testBadKey(): void
    {
        $this->expectException(Exception::class);

        SecretBox::encrypt('asdf', 'bad key');
    }

    public function testKeyRotation(): void
    {
        $key1 = random_bytes(32);
        $key2 = random_bytes(32);
        $key3 = random_bytes(32);

        $msg = 'Hello World!';
        $enc = SecretBox::encrypt($msg, $key1);

        $dec = SecretBox::decrypt($enc, [$key2, $key1]);
        $this->assertEquals($msg, $dec);

        $dec = SecretBox::decrypt($enc, [$key3, $key2, $key1]);
        $this->assertEquals($msg, $dec);
    }
}
