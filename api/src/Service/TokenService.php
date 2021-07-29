<?php


namespace App\Service;


use App\Model\Jwt;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\LocalFileReference;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class TokenService
{
    public static function getConfiguration(): Configuration {
        return Configuration::forAsymmetricSigner(
            new Signer\Rsa\Sha256(),
            LocalFileReference::file(__DIR__ . '/../../config/jwt/private.pem', 'b1otope'),
            InMemory::base64Encoded('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
            // LocalFileReference::file(__DIR__ . '/../../config/jwt/public.pem', 'b1otope')
        );
    }

    public static function generateToken($email): Jwt {
        $now   = new \DateTimeImmutable();
        $config = self::getConfiguration();

        $token = $config->builder()

            ->issuedAt($now)
            ->expiresAt($now->modify('+2400 hours'))
            ->withClaim('email', $email)
            ->getToken($config->signer(), $config->signingKey());

        $expire = $now->getTimestamp() + 2400 * 3600;
        $token = $token->toString();
        return new Jwt($token, $expire);

    }
}
