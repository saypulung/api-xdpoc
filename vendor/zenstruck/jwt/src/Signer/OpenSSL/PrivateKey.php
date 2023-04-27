<?php

namespace Zenstruck\JWT\Signer\OpenSSL;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PrivateKey extends Key
{
    /**
     * @param mixed       $value
     * @param string|null $passphrase
     */
    public function __construct($value, $passphrase = null)
    {
        if (is_string($value) && is_file($value) && is_readable($value)) {
            $value = file_get_contents($value);
        }

        if (!is_resource($value)) {
            $value = openssl_pkey_get_private($value, $passphrase);
        }

        parent::__construct($value);
    }
}
