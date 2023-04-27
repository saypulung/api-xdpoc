<?php

namespace Zenstruck\JWT;

use Zenstruck\JWT\Exception\ValidationFailed;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Validator
{
    /**
     * @param Token $token
     *
     * @throws ValidationFailed
     */
    public function validate(Token $token);
}
