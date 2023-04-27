<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Exception\Validation\ExpiredToken;
use Zenstruck\JWT\Exception\Validation\MissingClaim;
use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ExpiresAtValidator implements Validator
{
    private $currentTime;

    /**
     * @param \DateTime|null $currentTime
     */
    public function __construct(\DateTime $currentTime = null)
    {
        $this->currentTime = $currentTime ?: new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    public function validate(Token $token)
    {
        if (null === $token->expiresAt()) {
            throw new MissingClaim(Token::CLAIM_EXP, $token);
        }

        if ($token->isExpired($this->currentTime)) {
            throw new ExpiredToken($token);
        }
    }
}
