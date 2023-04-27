<?php

namespace Zenstruck\JWT\Exception\Validation;

use Zenstruck\JWT\Exception\ValidationFailed;
use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ExpiredToken extends ValidationFailed
{
    /**
     * @param Token           $token
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(Token $token, $message = 'Expired token.', \Exception $previous = null)
    {
        parent::__construct($token, $message, $previous);
    }
}
