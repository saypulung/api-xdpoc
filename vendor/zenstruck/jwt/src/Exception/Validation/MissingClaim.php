<?php

namespace Zenstruck\JWT\Exception\Validation;

use Zenstruck\JWT\Exception\ValidationFailed;
use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MissingClaim extends ValidationFailed
{
    /**
     * @param string          $claim
     * @param Token           $token
     * @param \Exception|null $previous
     */
    public function __construct($claim, Token $token, \Exception $previous = null)
    {
        parent::__construct($token, sprintf('Token missing claim "%s".', $claim), $previous);
    }
}
