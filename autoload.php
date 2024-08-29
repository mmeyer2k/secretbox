<?php

if (PHP_VERSION_ID > 80200) {
    return require 'src/SecretBoxSecureParam.php';
}

require 'src/SecretBox.php';