<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Exception\Validation\MissingClaim;
use Zenstruck\JWT\Exception\Validation\UnacceptableToken;
use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class NotBeforeValidator implements Validator
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
        if (null === $token->notBefore()) {
            throw new MissingClaim(Token::CLAIM_NBF, $token);
        }

        if (false === $token->isAcceptable($this->currentTime)) {
            throw new UnacceptableToken($token);
        }
    }
}
