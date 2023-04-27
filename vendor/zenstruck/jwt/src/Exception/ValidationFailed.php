<?php

namespace Zenstruck\JWT\Exception;

use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ValidationFailed extends InvalidToken
{
    /**
     * @param Token           $token
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(Token $token, $message = 'Token validation failed.', \Exception $previous = null)
    {
        parent::__construct($token, $message, $previous);
    }
}
