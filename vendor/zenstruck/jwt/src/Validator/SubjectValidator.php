<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class SubjectValidator extends ExpectedClaimValidator
{
    /**
     * @param mixed $expected
     */
    public function __construct($expected)
    {
        parent::__construct(Token::CLAIM_SUB, $expected);
    }
}
