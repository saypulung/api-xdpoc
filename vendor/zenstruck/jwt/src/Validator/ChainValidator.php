<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ChainValidator implements Validator
{
    /** @var Validator[] */
    private $validators;

    /**
     * @param Validator[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(Token $token)
    {
        foreach ($this->validators as $validator) {
            $validator->validate($token);
        }
    }
}
