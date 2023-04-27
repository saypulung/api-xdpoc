<?php

namespace Zenstruck\JWT\Exception;

use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class InvalidToken extends \DomainException implements Exception
{
    private $token;

    /**
     * @param Token           $token
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(Token $token, $message = 'Invalid token.', \Exception $previous = null)
    {
        $this->token = $token;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return Token
     */
    final public function token()
    {
        return $this->token;
    }
}
