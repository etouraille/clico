<?php


namespace App\Security\Authentication\Constraint;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;

final class MustNotBeExpired implements Constraint
{
    public function assert(Token $token): void
    {
        if (! $token instanceof UnencryptedToken) {
            throw new ConstraintViolation('You should pass a plain token');
        }

        if ( $token->isExpired(new \DateTimeImmutable()) ) {
            throw new ConstraintViolation('Token is expired');
        }
    }
}
