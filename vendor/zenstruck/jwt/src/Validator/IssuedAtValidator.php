<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class IssuedAtValidator extends ExpectedClaimValidator
{
    /**
     * @param mixed $expected
     */
    public function __construct($expected)
    {
        parent::__construct(Token::CLAIM_IAT, $expected);
    }
}
