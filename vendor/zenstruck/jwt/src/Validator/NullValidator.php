<?php

namespace Zenstruck\JWT\Validator;

use Zenstruck\JWT\Token;
use Zenstruck\JWT\Validator;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class NullValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function validate(Token $token)
    {
        // noop
    }
}
