<?php
namespace App\Security\Firewall;

use App\Security\Authentication\Token\JwtUserToken;
use App\Security\Authentication\Token\WsseUserToken;
use App\Service\TokenService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JwtListener
{
    protected $tokenStorage;
    protected $authenticationManager;
    protected $logger;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, LoggerInterface $logger)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->logger = $logger;
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $wsseRegex = '/Bearer (?P<token>[^"]+)/';
        if (!$request->headers->has('Authorization') || 1 !== preg_match($wsseRegex, $request->headers->get('Authorization'), $matches)) {
            return;
        }



        $token = new JwtUserToken();

        $jwt = TokenService::getConfiguration()->parser()->parse($matches['token']);
        $email = $jwt->claims()->get('email');
        $token->setUser($email);
        $token->jwt  = $matches['token'];

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
