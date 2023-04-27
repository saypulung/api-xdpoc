<?php

namespace Zenstruck\JWT\Exception\Validation;

use Zenstruck\JWT\Exception\ValidationFailed;
use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ClaimMismatch extends ValidationFailed
{
    /**
     * @param string          $claim
     * @param mixed           $actual
     * @param mixed           $expected
     * @param Token           $token
     * @param \Exception|null $previous
     */
    public function __construct($claim, $actual, $expected, Token $token, \Exception $previous = null)
    {
        parent::__construct($token, sprintf('Token claim "%s" should be "%s", got "%s".', $claim, $expected, $actual), $previous);
    }
}
