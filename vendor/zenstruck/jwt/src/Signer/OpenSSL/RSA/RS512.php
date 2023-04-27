<?php

namespace Zenstruck\JWT\Signer\OpenSSL\RSA;

use Zenstruck\JWT\Signer\OpenSSL\RSA;

/**
 * @author Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class RS512 extends RSA
{
    /**
     * {@inheritdoc}
     */
    protected function hashingAlgorithm()
    {
        return OPENSSL_ALGO_SHA512;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'RS512';
    }
}
