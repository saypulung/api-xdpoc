<?php

namespace Zenstruck\JWT;

use Zenstruck\JWT\Validator\AudienceValidator;
use Zenstruck\JWT\Validator\ChainValidator;
use Zenstruck\JWT\Validator\ExpectedClaimValidator;
use Zenstruck\JWT\Validator\ExpiresAtValidator;
use Zenstruck\JWT\Validator\HasClaimValidator;
use Zenstruck\JWT\Validator\IdValidator;
use Zenstruck\JWT\Validator\IssuedAtValidator;
use Zenstruck\JWT\Validator\IssuerValidator;
use Zenstruck\JWT\Validator\NotBeforeValidator;
use Zenstruck\JWT\Validator\NullValidator;
use Zenstruck\JWT\Validator\SubjectValidator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ValidatorBuilder
{
    /** @var Validator[] */
    private $validators = [];

    /**
     * @return Validator
     */
    public function create()
    {
        switch (count($this->validators)) {
            case 0:
                return new NullValidator();

            case 1:
                return $this->validators[0];
        }

        return new ChainValidator($this->validators);
    }

    /**
     * @param Validator $validator
     *
     * @return self
     */
    public function add(Validator $validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * @param string $claim
     *
     * @return self
     */
    public function has($claim)
    {
        return $this->add(new HasClaimValidator($claim));
    }

    /**
     * @param string $claim
     * @param mixed  $expected
     *
     * @return self
     */
    public function expect($claim, $expected)
    {
        return $this->add(new ExpectedClaimValidator($claim, $expected));
    }

    /**
     * @param mixed $expected
     *
     * @return self
     */
    public function issuedAt($expected)
    {
        return $this->add(new IssuedAtValidator($expected));
    }

    /**
     * @param mixed $expected
     *
     * @return self
     */
    public function subject($expected)
    {
        return $this->add(new SubjectValidator($expected));
    }

    /**
     * @param mixed $expected
     *
     * @return self
     */
    public function audience($expected)
    {
        return $this->add(new AudienceValidator($expected));
    }

    /**
     * @param mixed $expected
     *
     * @return self
     */
    public function issuer($expected)
    {
        return $this->add(new IssuerValidator($expected));
    }

    /**
     * @param mixed $expected
     *
     * @return self
     */
    public function id($expected)
    {
        return $this->add(new IdValidator($expected));
    }

    /**
     * @param \DateTime|null $currentTime
     *
     * @return self
     */
    public function expiresAt(\DateTime $currentTime = null)
    {
        return $this->add(new ExpiresAtValidator($currentTime));
    }

    /**
     * @param \DateTime|null $currentTime
     *
     * @return self
     */
    public function notBefore(\DateTime $currentTime = null)
    {
        return $this->add(new NotBeforeValidator($currentTime));
    }
}
