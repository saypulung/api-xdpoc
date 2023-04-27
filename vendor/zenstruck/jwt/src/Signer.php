<?php

namespace Zenstruck\JWT;

/**
 * @author Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Signer
{
    /**
     * @param string $input
     * @param mixed  $key
     *
     * @return string
     */
    public function sign($input, $key);

    /**
     * @param string $input
     * @param string $signature
     * @param mixed  $key
     *
     * @return bool
     */
    public function verify($input, $signature, $key);

    /**
     * @return string
     */
    public function name();
}
