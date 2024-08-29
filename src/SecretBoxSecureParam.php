<?php

declare(strict_types=1);

class SecretBox
{
    public static function encrypt(
        #[SensitiveParameter] string $message,
        #[SensitiveParameter] string $key,
    ): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($message, $nonce, $key);

        return $nonce . $cipher;
    }

    public static function decrypt(
        string $encrypted,
        #[SensitiveParameter] array $keys,
    ): string
    {
        $nonce = substr($encrypted, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($encrypted, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        foreach ($keys as $key) {
            $plain = sodium_crypto_secretbox_open($cipher, $nonce, $key);
            if ($plain !== false) {
                return $plain;
            }
        }

        throw new Exception('SecretBox: could not decrypt message');
    }
}
