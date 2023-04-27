<?php

namespace Zenstruck\JWT\Signer\OpenSSL;

use Zenstruck\JWT\Signer\OpenSSL;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ECDSA extends OpenSSL
{
    /**
     * {@inheritdoc}
     */
    protected function allowedType()
    {
        return OPENSSL_KEYTYPE_EC;
    }
}
