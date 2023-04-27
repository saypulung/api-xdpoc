<?php

namespace Zenstruck\JWT\Signer\OpenSSL;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class Key
{
    private $value;
    private $type;

    /**
     * @param resource $value
     */
    public function __construct($value)
    {
        if (!is_resource($value)) {
            throw new \InvalidArgumentException('Invalid key.');
        }

        $keyDetails = openssl_pkey_get_details($value);

        if (!is_array($keyDetails) || !isset($keyDetails['type'])) {
            throw new \InvalidArgumentException('Invalid key.');
        }

        $this->value = $value;
        $this->type = $keyDetails['type'];
    }

    /**
     * @return resource
     */
    final public function get()
    {
        return $this->value;
    }

    final public function type()
    {
        return $this->type;
    }
}
