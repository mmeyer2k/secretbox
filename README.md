# secretbox

A minimalist [libsodium](https://libsodium.gitbook.io/doc/) secretbox implementation for PHP, supporting key rotation.

[![PHP Tests](https://github.com/mmeyer2k/secretbox/actions/workflows/php-tests.yml/badge.svg)](https://github.com/mmeyer2k/secretbox/actions/workflows/php-tests.yml)

## Features
- Encrypt and decrypt messages using libsodium's secretbox
- Support for key rotation (multiple keys for decryption)
- Simple API for secure key management

## Installation

Install via Composer:

```bash
composer require mmeyer2k/secretbox
```

Requires PHP 8.2+ and the Sodium extension.

## Usage

Basic encryption and decryption:

```php
use \Mmeyer2k\SecretBox\SecretBox;

$key = random_bytes(32); // 32 bytes required

$ciphertext = SecretBox::encrypt('secret message', $key);
$plaintext = SecretBox::decrypt($ciphertext, $key);
```

## Key Management

### Creating a Key
Generate a secure 32-byte key:

```bash
head -c 32 /dev/urandom | base64 -w 0
```

### Storing a Key
Store keys in environment variables or configuration files as base64 strings. Decode before use:

```php
$key = base64_decode('[your base64 key]');
```

### Key Rotation
Support multiple keys for seamless rotation:

```php
$plaintext = SecretBox::decrypt($ciphertext, [
    $oldKey,
    $newKey,
]);
```
Decryption will succeed with any valid key in the array.

## Error Handling

If decryption fails (e.g., no matching key), a `\SodiumException` is thrown:

```php
try {
    $plaintext = SecretBox::decrypt($ciphertext, $key);
} catch (\SodiumException $e) {
    // Handle decryption failure
}
```

## License

MIT
