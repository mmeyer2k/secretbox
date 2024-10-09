# secretbox
A minimalist libsodium secretbox implementation with key rotation.

## install

## usage

## key rotation

Easily rotate keys by passing allowable decryption keys in an array.
```php
$dec = SecretBox::decrypt($ciphertext, [
    'key0'
    'key1',
    'key2'
]);
```

If decryption is successful, the index of the correct key will be passed by reference through the `index` parameter.
```php
$keys = [
    'key0'
    'key1',
    'key2'
];

$dec = SecretBox::decrypt($ciphertext, $keys, $index);

// For example, $index will equal 0 if the first key was successful at decrypting the ciphertext
```