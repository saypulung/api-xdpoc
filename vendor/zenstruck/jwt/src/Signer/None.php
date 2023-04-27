<?php

namespace Zenstruck\JWT\Signer;

use Zenstruck\JWT\Signer;

/**
 * @author Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class None implements Signer
{
    /**
     * {@inheritdoc}
     */
    public function sign($input, $key)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function verify($input, $signature, $key)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'NONE';
    }
}
