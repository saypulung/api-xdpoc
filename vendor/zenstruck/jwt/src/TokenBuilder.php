<?php

namespace Zenstruck\JWT;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TokenBuilder
{
    private $claims = [];
    private $headers = [Token::HEADER_TYP => 'JWT'];

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function set($key, $value)
    {
        $this->claims[$key] = $value;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function issuer($value)
    {
        return $this->set(Token::CLAIM_ISS, $value);
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function subject($value)
    {
        return $this->set(Token::CLAIM_SUB, $value);
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function audience($value)
    {
        return $this->set(Token::CLAIM_AUD, $value);
    }

    /**
     * @param \DateTime $time
     *
     * @return self
     */
    public function expiresAt(\DateTime $time)
    {
        return $this->set(Token::CLAIM_EXP, $time->getTimestamp());
    }

    /**
     * @param \DateTime $time
     *
     * @return self
     */
    public function notBefore(\DateTime $time)
    {
        return $this->set(Token::CLAIM_NBF, $time->getTimestamp());
    }

    /**
     * @param \DateTime|null $time
     *
     * @return self
     */
    public function issuedAt(\DateTime $time = null)
    {
        if (null === $time) {
            $time = new \DateTime('now');
        }

        return $this->set(Token::CLAIM_IAT, $time->getTimestamp());
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function id($value)
    {
        return $this->set(Token::CLAIM_JTI, $value);
    }

    /**
     * @return Token
     */
    public function create()
    {
        return new Token($this->claims, $this->headers);
    }
}
