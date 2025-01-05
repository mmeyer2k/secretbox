<?php

declare(strict_types=1);

namespace Mmeyer2k\SecretBox;

use SodiumException;

class SecretBox
{
    /**
     * Encrypt secretbox message
     * @param string $message
     * @param string $key
     * @return string
     * @throws SodiumException
     */
    public static function encrypt(string $message, string $key): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($message, $nonce, $key);

        return $nonce . $cipher;
    }

    /**
     * Decrypt secretbox message
     * @param string $encrypted
     * @param array|string $keys
     * @param $index
     * @return string
     * @throws SodiumException
     */
    public static function decrypt(string $encrypted, array|string $keys, &$index = null): string
    {
        $keys = is_string($keys) ? [$keys] : $keys;
        $nonce = substr($encrypted, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($encrypted, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        foreach ($keys as $i => $key) {
            $plain = sodium_crypto_secretbox_open($cipher, $nonce, $key);
            if ($plain !== false) {
                $index = $i;
                return $plain;
            }
        }

        throw new SodiumException('SecretBox: decryption failed');
    }
}
