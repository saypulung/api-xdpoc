<?php

namespace Zenstruck\JWT;

use Zenstruck\JWT\Exception\MalformedToken;
use Zenstruck\JWT\Exception\UnverifiedToken;
use Zenstruck\JWT\Exception\ValidationFailed;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Token
{
    const HEADER_TYP = 'typ';
    const HEADER_ALG = 'alg';

    const CLAIM_ISS = 'iss';
    const CLAIM_SUB = 'sub';
    const CLAIM_AUD = 'aud';
    const CLAIM_EXP = 'exp';
    const CLAIM_NBF = 'nbf';
    const CLAIM_IAT = 'iat';
    const CLAIM_JTI = 'jti';

    private $headers;
    private $claims;
    private $signature;

    /**
     * @param string $token
     *
     * @return self
     *
     * @throws MalformedToken
     */
    public static function fromString($token)
    {
        if (!is_string($token)) {
            throw new MalformedToken($token);
        }

        $parts = explode('.', $token);

        if (3 !== count($parts)) {
            throw new MalformedToken($token);
        }

        try {
            $headers = self::jsonDecode($parts[0]);
            $claims = self::jsonDecode($parts[1]);
        } catch (\InvalidArgumentException $e) {
            throw new MalformedToken($token, $e);
        }

        if (!is_array($headers) || !is_array($claims)) {
            throw new MalformedToken($token);
        }

        return new self($claims, $headers, $parts[2]);
    }

    /**
     * @param array       $claims
     * @param array       $headers
     * @param string|null $signature
     */
    public function __construct(array $claims, array $headers = [], $signature = null)
    {
        $this->headers = $headers;
        $this->claims = $claims;
        $this->signature = $signature;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s.%s', $this->createPayload(), $this->signature);
    }

    /**
     * @param Signer $signer
     * @param mixed  $key
     *
     * @return self
     */
    public function sign(Signer $signer, $key)
    {
        $this->headers[self::HEADER_ALG] = $signer->name();
        $this->signature = self::encode($signer->sign($this->createPayload(), $key));

        return $this;
    }

    /**
     * @param Signer $signer
     * @param mixed  $key
     *
     * @return self
     *
     * @throws UnverifiedToken
     */
    public function verify(Signer $signer, $key)
    {
        if ($this->algorithm() !== $signer->name()) {
            throw new UnverifiedToken($this);
        }

        if (!$signer->verify($this->createPayload(), $this->decode($this->signature), $key)) {
            throw new UnverifiedToken($this);
        }

        return $this;
    }

    /**
     * @param Validator $validator
     *
     * @return self
     *
     * @throws ValidationFailed
     */
    public function validate(Validator $validator)
    {
        $validator->validate($this);

        return $this;
    }

    /**
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function claims()
    {
        return $this->claims;
    }

    /**
     * @return string
     */
    public function algorithm()
    {
        return strtoupper($this->getHeader(self::HEADER_ALG, 'NONE'));
    }

    /**
     * @return string|null
     */
    public function issuer()
    {
        return $this->get(self::CLAIM_ISS);
    }

    /**
     * @return string|null
     */
    public function subject()
    {
        return $this->get(self::CLAIM_SUB);
    }

    /**
     * @return string|null
     */
    public function audience()
    {
        return $this->get(self::CLAIM_AUD);
    }

    /**
     * @return string|null
     */
    public function id()
    {
        return $this->get(self::CLAIM_JTI);
    }

    /**
     * @return \DateTime|null
     */
    public function expiresAt()
    {
        return $this->getDateClaim(self::CLAIM_EXP);
    }

    /**
     * @return \DateTime|null
     */
    public function issuedAt()
    {
        return $this->getDateClaim(self::CLAIM_IAT);
    }

    /**
     * @return \DateTime|null
     */
    public function notBefore()
    {
        return $this->getDateClaim(self::CLAIM_NBF);
    }

    /**
     * @param \DateTime|null $currentTime
     *
     * @return bool
     */
    public function isExpired(\DateTime $currentTime = null)
    {
        if (null === $expiresAt = $this->get(self::CLAIM_EXP)) {
            return false;
        }

        $currentTime = $currentTime ?: new \DateTime('now');

        return $expiresAt < $currentTime->getTimestamp();
    }

    /**
     * @param \DateTime|null $currentTime
     *
     * @return bool
     */
    public function isAcceptable(\DateTime $currentTime = null)
    {
        if (null === $notBefore = $this->get(self::CLAIM_NBF)) {
            return true;
        }

        $currentTime = $currentTime ?: new \DateTime('now');

        return $currentTime->getTimestamp() > $notBefore;
    }

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->claims[$key])) {
            return $this->claims[$key];
        }

        return $default;
    }

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getHeader($key, $default = null)
    {
        if (isset($this->headers[$key])) {
            return $this->headers[$key];
        }

        return $default;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private static function encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private static function decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * @param string $data
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private static function jsonDecode($data)
    {
        $decoded = json_decode(self::decode($data), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf('Error decoding JSON string "%s".', $data));
        }

        return $decoded;
    }

    /**
     * @return string
     */
    private function createPayload()
    {
        return sprintf('%s.%s',
            self::encode(json_encode($this->headers(), JSON_UNESCAPED_SLASHES)),
            self::encode(json_encode($this->claims(), JSON_UNESCAPED_SLASHES))
        );
    }

    /**
     * @param string $claim
     *
     * @return \DateTime|null
     */
    private function getDateClaim($claim)
    {
        if (null === $value = $this->get($claim)) {
            return null;
        }

        return \DateTime::createFromFormat('U', $value);
    }
}
