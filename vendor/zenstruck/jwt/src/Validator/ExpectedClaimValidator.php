<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Exception\Validation\ClaimMismatch;
use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ExpectedClaimValidator implements Validator
{
    private $claim;
    private $expected;

    /**
     * @param string $claim
     * @param mixed  $expected
     */
    public function __construct($claim, $expected)
    {
        $this->claim = $claim;
        $this->expected = $expected;
    }

    /**
     * {@inheritdoc}
     */
    final public function validate(Token $token)
    {
        (new HasClaimValidator($this->claim))->validate($token);

        $actual = $token->get($this->claim);

        if ($this->expected !== $actual) {
            throw new ClaimMismatch($this->claim, $actual, $this->expected, $token);
        }
    }
}
