<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Token;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class AudienceValidator extends ExpectedClaimValidator
{
    /**
     * @param mixed $expected
     */
    public function __construct($expected)
    {
        parent::__construct(Token::CLAIM_AUD, $expected);
    }
}
