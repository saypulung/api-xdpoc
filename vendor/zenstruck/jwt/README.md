# zenstruck/jwt

[![Build Status](http://img.shields.io/travis/kbond/php-jwt.svg?style=flat-square)](https://travis-ci.org/kbond/php-jwt)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/kbond/php-jwt.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/php-jwt/)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/kbond/php-jwt.svg?style=flat-square)](https://scrutinizer-ci.com/g/kbond/php-jwt/)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/5417ff98-be77-4f82-b4ff-5b2650c56583.svg?style=flat-square)](https://insight.sensiolabs.com/projects/5417ff98-be77-4f82-b4ff-5b2650c56583)
[![StyleCI](https://styleci.io/repos/43430831/shield)](https://styleci.io/repos/43430831)
[![Latest Stable Version](http://img.shields.io/packagist/v/zenstruck/jwt.svg?style=flat-square)](https://packagist.org/packages/zenstruck/jwt)
[![License](http://img.shields.io/packagist/l/zenstruck/jwt.svg?style=flat-square)](https://packagist.org/packages/zenstruck/jwt)

Provides a lightweight implementation of the JWS ([JSON Web Signature](http://tools.ietf.org/html/draft-jones-json-web-signature-04))
specification. This library is a fork of [namshi/jose](https://github.com/namshi/jose).

## Requirements

1. **PHP 5.4.8+** (tested on 5.4, 5.5, 5.6, 7 and HHVM)
2. *(Optional)* OpenSSL extension (when using RSA/ECDSA signers)
3. *(Optional)* [symfony/polyfill-php56](https://packagist.org/packages/symfony/polyfill-php56) library (when HMAC signers
   in PHP versions less than 5.6)

## Installation

    composer require zenstruck/jwt

**When using an HMAC signer in PHP versions less than 5.6**

    composer require symfony/polyfill-php56

## Basic Usage

1. Create, encode and send a token to the user:

    ```php
    use Zenstruck\JWT\Token;
    use Zenstruck\JWT\Signer\HMAC\HS256;

    // create the token
    $token = new Token([
        'username' => 'kevin', // custom claim
        'iss' => 'zenstruck', // set issuer
        'exp' => time() + 86400, // set expiry claim to 1 day from now
    ]);

    // can access claims
    $token->get('username'); // kevin
    $token->get('non-existant'); // null

    // sign the token
    $token->sign(new HS256(), 'my secret key');

    $encodedTokenForUser = (string) $token;

    // ...pass to user
    ```

2. Fetch encoded token from user, decode, verify, validate and access custom claims

    ```php
    use Zenstruck\JWT\Token;
    use Zenstruck\JWT\Signer\HMAC\HS256;
    use Zenstuck\JWT\Validator\ExpiresAtValidator;
    use Zenstuck\JWT\Validator\IssuerValidator;
    use Zenstruck\JWT\Exception\MalformedToken;
    use Zenstruck\JWT\Exception\UnverifiedToken;
    use Zenstruck\JWT\Exception\Validation\ExpiredToken;
    use Zenstruck\JWT\Exception\ValidationFailed;

    $encodedTokenFromUser = // ...fetched from user

    try {
        $decodedToken = Token::fromString($encodedTokenFromUser);
    } catch (MalformedToken $e) {
        // token is not correctly formed
    }

    // at this point $decodedToken is a JWT but is not yet verified or validated

    try {
        $decodedToken->verify(new HS256(), 'my secret key');
    } catch (UnverifiedToken $e) {
        // token could not be verified
    }

    try {
        $decodedToken->validate(new ExpiresAtValidator());
        $decodedToken->validate(new IssuerValidator('zenstruck'));
    } catch (ExpiredToken $e) {
        // the token has expired
    } catch (ValidationFailed $e) {
        // token is invalid - in this case, the issuer does not match
    }

    // can access claims
    $token->get('username'); // kevin
    $token->get('non-existant'); // null
    ```

## Token Builder

```php
use Zenstruck\JWT\TokenBuilder;

$token = (new TokenBuilder())
    ->issuer('kevin')
    ->subject('zenstruck\jwt')
    ->audience('php community')
    ->expiresAt(new \DateTime('+1 day'))
    ->notBefore(new \DateTime('+1 hour'))
    ->issuedAt() // can pass \DateTime object - uses current time by default
    ->id('foo')
    ->set('foo', 'bar') // set custom claims
    ->create(); // instance of Zenstruck\JWT\Token
```

## Signers

### HMAC

These signers require an identical key string to be used for signing and validating.

| Algorithm | Signer Class                      |
| --------- | --------------------------------- |
| HS256     | `Zenstruck\JWT\Signer\HMAC\HS256` |
| HS384     | `Zenstruck\JWT\Signer\HMAC\HS384` |
| HS512     | `Zenstruck\JWT\Signer\HMAC\HS512` |

#### Usage

```php
$token = // ... instance of Zenstruck\JWT\Token
$signer = // an instance of one of the classes in the table above

$token->sign($signer, 'my secret key');
$token->verify($signer, 'my secret key'); // verified
$token->verify($signer, 'invalid secret key'); // unverified - exception thrown
```

### RSA/ECDSA (OpenSSL)

These signers require a private key for signing and a public key for verifying.

* **PrivateKey**: a `string` (key contents or filename), `resource` or instance of
  `Zenstruck\JWT\Signer\OpenSSL\PrivateKey`
* **PublicKey**: a `string` (key contents or filename), `resource` or instance of
  `Zenstruck\JWT\Signer\OpenSSL\PublicKey`
* **Keychain**: instance of `Zenstruck\JWT\Signer\OpenSSL\Keychain` contains both a public and private key.
  Can be passed as the key to both `Signer::sign()` and `Signer::verify()`.

| Algorithm | Signer Class                               |
| --------- | ------------------------------------------ |
| RS256     | `Zenstruck\JWT\Signer\OpenSSL\RSA\RS256`   |
| RS384     | `Zenstruck\JWT\Signer\OpenSSL\RSA\RS384`   |
| RS512     | `Zenstruck\JWT\Signer\OpenSSL\RSA\RS512`   |
| ES256     | `Zenstruck\JWT\Signer\OpenSSL\ECDSA\ES256` |
| ES384     | `Zenstruck\JWT\Signer\OpenSSL\ECDSA\ES384` |
| ES512     | `Zenstruck\JWT\Signer\OpenSSL\ECDSA\ES512` |

#### Usage

```php
$token = // ... instance of Zenstruck\JWT\Token
$signer = // an instance of one of the classes in the table above
$privateKey = // can be string, resource, filename, instance of Zenstruck\JWT\Signer\OpenSSL\PrivateKey, instance of Zenstruck\JWT\Signer\OpenSSL\Keychain
$publicKey = // can be string, resource, filename, instance of Zenstruck\JWT\Signer\OpenSSL\PublicKey, instance of Zenstruck\JWT\Signer\OpenSSL\Keychain

$token->sign($signer, $privateKey);
$token->verify($signer, $publicKey); // verified
$token->verify($signer, '/path/to/unmatched/public/key'); // unverified - exception thrown
```

##### Keychain

A keychain contains both a public and private key. When passed a keychain as the key, the signer uses
the proper key for the operation.

```php
use Zenstruck\JWT\Signer\OpenSSL\Keychain;

$token = // ... instance of Zenstruck\JWT\Token
$signer = // an instance of one of the classes in the table above
$privateKey = // can be string, resource, filename, instance of Zenstruck\JWT\Signer\OpenSSL\PrivateKey
$publicKey = // can be string, resource, filename, instance of Zenstruck\JWT\Signer\OpenSSL\PublicKey

$keychain = new Keychain($publicKey, $privateKey, 'my passphrase');

$token->sign($signer, $keychain);
$token->verify($signer, $keychain); // verified
```

## Validation

| Validator                                    | Description                                                  |
| -------------------------------------------- | ------------------------------------------------------------ |
| `Zenstruck\JWT\Validator\IssuerValidator`    | Ensures `iss` claim exists and matches expected value        |
| `Zenstruck\JWT\Validator\SubjectValidator`   | Ensures `sub` claim exists and matches expected value        |
| `Zenstruck\JWT\Validator\AudienceValidator`  | Ensures `aud` claim exists and matches expected value        |
| `Zenstruck\JWT\Validator\ExpiresAtValidator` | Ensures `exp` claim exists is not greater than expected time |
| `Zenstruck\JWT\Validator\NotBeforeValidator` | Ensures `nbf` claim exists is not less than expected time    |
| `Zenstruck\JWT\Validator\IssuedAtValidator`  | Ensures `iat` claim exists and matches expected value        |
| `Zenstruck\JWT\Validator\IdAtValidator`      | Ensures `jti` claim exists and matches expected value        |
| `Zenstruck\JWT\Validator\ChainValidator`     | Combines any of the above validators together                |

### Usage

```php
use Zenstruck\JWT\Validator\IssuerValidator;
use Zenstruck\JWT\Validator\AudienceValidator;
use Zenstruck\JWT\Validator\ChainValidator;

$token = // ... instance of Zenstruck\JWT\Token
$validator = new ChainValidator([new IssuerValidator(), new AudienceValidator()]);

try {
    $token->validate($validator);
} catch (ValidationFailed $e) {
    $reason = $e->getMessage();
}
```

## ValidatorBuilder

```php
$validator = (new ValidatorBuilder())
    ->issuer('kevin')
    ->subject('zenstruck\jwt')
    ->audience('php community')
    ->expiresAt()
    ->notBefore()
    ->issuedAt(time())
    ->id('foo')
    ->create(); // instance of Zenstruck\JWT\Validator\ChainValidator
```
