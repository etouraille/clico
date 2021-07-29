<?php

namespace App\Security\Authentication\Provider;

use App\Security\Authentication\Constraint\MustNotBeExpired;
use App\Security\Authentication\Token\JwtUserToken;
use App\Service\TokenService;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Lcobucci\JWT\Validation\Constraint\SignedWith;

class JwtProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cachePool;
    private $logger;

    public function __construct(UserProviderInterface $userProvider, CacheItemPoolInterface $cachePool, LoggerInterface $logger)
    {
        $this->userProvider = $userProvider;
        $this->cachePool = $cachePool;
        $this->logger = $logger;
    }

    public function authenticate(TokenInterface $token): JwtUserToken
    {
        // The loadUserByIdentifier() and getUserIdentifier() methods were
        // introduced in Symfony 5.3. In previous versions they were called
        // loadUserByUsername() and getUsername() respectively
        $user = $this->userProvider->loadUserByIdentifier($token->getUserIdentifier());


        if ($user && $this->validateJwt($token->jwt)) {
            $authenticatedToken = new JwtUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The JWT authentication failed.');
    }

    /**
     * This function is specific to Wsse authentication and is only used to help this example
     *
     * For more information specific to the logic here, see
     * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
     */
    protected function validateJwt($jwt): bool
    {
        try {
            $jwt = TokenService::getConfiguration()->parser()->parse($jwt);
            TokenService::getConfiguration()->validator()->assert($jwt, new MustNotBeExpired());
        } catch(\Exception $e) {

            return false;
        }
        return true;
    }

    public function supports(TokenInterface $token): bool
    {
        return $token instanceof JwtUserToken;
    }
}
