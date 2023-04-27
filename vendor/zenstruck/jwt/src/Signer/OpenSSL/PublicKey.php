<?php

namespace Zenstruck\JWT\Signer\OpenSSL;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class PublicKey extends Key
{
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        if (is_string($value) && is_file($value) && is_readable($value)) {
            $value = file_get_contents($value);
        }

        if (!is_resource($value)) {
            $value = openssl_pkey_get_public($value);
        }

        parent::__construct($value);
    }
}
