<?php

namespace Zenstruck\JWT\Signer;

use Zenstruck\JWT\Signer;

/**
 * @author Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class HMAC implements Signer
{
    /**
     * {@inheritdoc}
     */
    public function sign($input, $key)
    {
        return hash_hmac($this->hashingAlgorithm(), $input, (string) $key, true);
    }

    /**
     * {@inheritdoc}
     */
    public function verify($input, $signature, $key)
    {
        if (!function_exists('hash_equals')) {
            throw new \RuntimeException('hash_equals is only available in PHP 5.6+, install symfony/polyfill-php56 in PHP 5.4/5.5');
        }

        $signedInput = $this->sign($input, $key);

        return hash_equals($signedInput, $signature);
    }

    /**
     * @return string
     */
    abstract protected function hashingAlgorithm();
}
