<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Exception\Validation\MissingClaim;
use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class HasClaimValidator implements Validator
{
    private $claim;

    /**
     * @param string $claim
     */
    public function __construct($claim)
    {
        $this->claim = $claim;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(Token $token)
    {
        if (null === $token->get($this->claim)) {
            throw new MissingClaim($this->claim, $token);
        }
    }
}
