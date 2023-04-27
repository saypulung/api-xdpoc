<?php

namespace Zenstruck\JWT\Exception;

use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class UnverifiedToken extends InvalidToken
{
    /**
     * @param Token           $token
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(Token $token, $message = 'Unverified token.', \Exception $previous = null)
    {
        parent::__construct($token, $message, $previous);
    }
}
