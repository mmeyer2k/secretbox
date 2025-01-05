# secretbox
A minimalist libsodium secretbox implementation with key rotation.

## install

```bash
composer require mmeyer2k/secretbox
```

## usage
```php
use \Mmeyer2k\SecretBox\SecretBox;

$key = random_bytes(32);

$enc = SecretBox::encrypt('secret message', $key);
$dec = SecretBox::decrypt($enc, $key);
```

## keys

### create
SecretBox expects keys to be strings with 32 bytes of pseudorandom-ness.
```bash
head -c 32 /dev/urandom | base64 -w 0 | xargs echo
```

### store

In code or environment files, it is best to store keys in an encoded format.
```php
$key = base64_decode("[your base64 key]");
```

### rotate
Easily rotate keys by passing allowable decryption keys in an array.
```php
$dec = SecretBox::decrypt($ciphertext, [
    'key 0',
    'key 1',
    'key 2',
]);
```

If decryption is successful, the index of the correct key will be passed by reference through the optional `index` parameter.
In this example, `$index` will equal 0 if the first key was successful
```php
$dec = SecretBox::decrypt($ciphertext, $keys, $index);
```