<?php

declare(strict_types=1);

namespace Mmeyer2k\SecretBox;

use Random\RandomException;
use SodiumException;

class SecretBox
{
    /**
     * Encrypt secretbox message
     * @param string $message
     * @param string $key
     * @return string
     * @throws SodiumException|RandomException
     */
    public static function encrypt(string $message, string $key): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($message, $nonce, $key);

        sodium_memzero($key);

        return $nonce . $cipher;
    }

    /**
     * Decrypt secretbox message
     * @param string $encrypted
     * @param array<string>|string $keys
     * @return string
     * @throws SodiumException
     */
    public static function decrypt(string $encrypted, array|string $keys): string
    {
        $keys = is_string($keys) ? [$keys] : $keys;
        $nonce = substr($encrypted, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($encrypted, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $plain = false;

        foreach ($keys as &$key) {
            if (false === $plain) {
                $plain = sodium_crypto_secretbox_open($cipher, $nonce, $key);
            }

            sodium_memzero($key);
        }

        if (false === $plain) {
            throw new SodiumException('SecretBox: decryption failed');
        }

        return $plain;
    }
}
