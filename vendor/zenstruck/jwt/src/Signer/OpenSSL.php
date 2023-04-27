<?php

namespace Zenstruck\JWT\Signer;

use Zenstruck\JWT\Signer;
use Zenstruck\JWT\Signer\OpenSSL\Key;
use Zenstruck\JWT\Signer\OpenSSL\Keychain;
use Zenstruck\JWT\Signer\OpenSSL\PrivateKey;
use Zenstruck\JWT\Signer\OpenSSL\PublicKey;

/**
 * @author Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class OpenSSL implements Signer
{
    public function __construct()
    {
        if (!extension_loaded('openssl')) {
            throw new \RuntimeException('The openssl PHP extension must be enabled to use RSA/ECDSA signers');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sign($input, $key)
    {
        if ($key instanceof Keychain) {
            $key = $key->privateKey();
        }

        if (!$key instanceof PrivateKey) {
            $key = new PrivateKey($key);
        }

        $this->verifyType($key);

        openssl_sign($input, $signature, $key->get(), $this->hashingAlgorithm());

        return $signature;
    }

    /**
     * {@inheritdoc}
     */
    public function verify($input, $signature, $key)
    {
        if ($key instanceof Keychain) {
            $key = $key->publicKey();
        }

        if (!$key instanceof PublicKey) {
            $key = new PublicKey($key);
        }

        $result = openssl_verify($input, $signature, $key->get(), $this->hashingAlgorithm());

        if (-1 === $result) {
            throw new \RuntimeException('Unknown error during verification.');
        }

        return (bool) $result;
    }

    /**
     * @return int
     */
    abstract protected function hashingAlgorithm();

    /**
     * @return int
     */
    abstract protected function allowedType();

    /**
     * @param Key $key
     */
    private function verifyType(Key $key)
    {
        if ($this->allowedType() !== $key->type()) {
            throw new \InvalidArgumentException(sprintf('Invalid key type.'));
        }
    }
}
