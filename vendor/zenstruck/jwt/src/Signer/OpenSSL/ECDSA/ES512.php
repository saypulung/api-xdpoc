<?php

namespace Zenstruck\JWT\Signer\OpenSSL\ECDSA;

use Zenstruck\JWT\Signer\OpenSSL\ECDSA;

/**
 * @author Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ES512 extends ECDSA
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
        return 'ES512';
    }
}
