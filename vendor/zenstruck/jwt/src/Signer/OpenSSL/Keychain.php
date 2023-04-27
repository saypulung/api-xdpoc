<?php

namespace Zenstruck\JWT\Signer\OpenSSL;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Keychain
{
    private $publicKey;
    private $privateKey;

    /**
     * @param PublicKey|mixed  $publicKey
     * @param PrivateKey|mixed $privateKey
     * @param string|null      $passphrase
     */
    public function __construct($publicKey, $privateKey, $passphrase = null)
    {
        $this->publicKey = $publicKey instanceof PublicKey ? $publicKey : new PublicKey($publicKey);
        $this->privateKey = $privateKey instanceof PrivateKey ? $privateKey : new PrivateKey($privateKey, $passphrase);
    }

    /**
     * @return PublicKey
     */
    public function publicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return PrivateKey
     */
    public function privateKey()
    {
        return $this->privateKey;
    }
}
