<?php


namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Model\Jwt;
use App\Model\Login;
use App\Model\Password;
use App\Model\Passwords;
use App\Model\Subscribe;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthController
{
    /**
     * @Post("subscribe")
     * @ParamConverter(
    "subscribe",
    class="App\Model\Subscribe",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @param Subscribe $subscribe
     * @param EntityManagerInterface $em
     * @return Jwt|JsonResponse
     * @param Subscribe $subscribe
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $encoder
     * @return Jwt
     */
    public function subscribe(Subscribe $subscribe, EntityManagerInterface $em, UserPasswordHasherInterface $encoder)
    {
        // si l'utilisteur n'existe pas.
        $userRepository = $em->getRepository(User::class);
        if ($userRepository->userExists($subscribe->getEmail())) {
            throw new BadRequestHttpException('User exists');
        }
        // encode password and set user account.
        $user = new User();
        $user->setEmail($subscribe->getEmail());
        $user->setName($subscribe->getName());
        $user->setPassword($encoder->hashPassword($user, $subscribe->getPassword()));
        $em->persist($user);
        $em->flush();
        // on récupère le token

        $token = TokenService::generateToken($subscribe->getEmail());
        $tokenEntity = new Token();
        $tokenEntity->setExpire($token->getExpire());
        $tokenEntity->setJwt($token->getToken());
        $tokenEntity->setUser($user);
        $em->persist($user);
        $em->flush();

        return $token;
    }

    /**
     * @Post("login")
     * @ParamConverter(
    "login",
    class="App\Model\Login",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @param Login $login
     * @param EntityManagerInterface $em
     * @return Jwt|JsonResponse
     */
    public function login(Login $login, EntityManagerInterface $em, UserPasswordHasherInterface $encoder)
    {
        // On verifie que le compte a bien le droit de se connecter.
        $repo = $em->getRepository(User::class);
        $user = $repo->getUserByEmail($login->getUsername());
        if (!($user instanceof User) ||
            !($encoder->isPasswordValid($user, $login->getPassword()))) {
            throw new BadRequestHttpException('Bad request');
        }
        // On cherche le dernier token associé
        $tokenRepo = $em->getRepository(Token::class);
        $token = $tokenRepo->getValidTokenForUser($user);
        if ($token instanceof Token) {
            return new Jwt($token->getJwt(), $token->getExpire());
        } else {
            $jwt = TokenService::generateToken($user->getEmail());
            $token = new Token();
            $token->setExpire($jwt->getExpire());
            $token->setJwt($jwt->getToken());
            $token->setUser($user);
            $em->persist($token);
            $em->flush();
            return $jwt;
        }
    }

    /**
     * @Route("/api/unlog", name="unlog_route")
     */
    public function unlog(TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $user = $tokenStorage->getToken()->getUser();
        if ($user) {
            $tokens = $em->getRepository(Token::class)->getTokensForUser($user);
            foreach ($tokens as $token) {
                $em->remove($token);
            }
            $em->flush();
            return ['success' => true];
        } else {
            return ['error' => 'no user'];
        }

    }

    /**
     * @Route("/api/ping", name="ping_route")
     */
    public function ping(\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $token): User
    {
        return $token->getToken()->getUser();
    }

    /**
     * @Post("/api/is-password-valid")
     * @ParamConverter(
    "password",
    class="App\Model\Password",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @param Password $password
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function passwordIsValid(Password $password, EntityManagerInterface $em, UserPasswordHasherInterface $encoder, TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();
        if ($user) {
            $ret = $encoder->isPasswordValid($user, $password->getPassword());
            return ['valid' => $ret];
        } else {
            return ['valid' => false];
        }
    }

    /**
     * @Post("/api/change-password")
     * @ParamConverter(
    "passwords",
    class="App\Model\Passwords",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @param Passwords $passwords
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function changePassword(Passwords $passwords, EntityManagerInterface $em, UserPasswordHasherInterface $encoder, TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();
        if ($user) {
            $ret = $encoder->isPasswordValid($user, $passwords->getOldPassword());
            if(!$ret) {
                throw new BadRequestHttpException('old password is not valid');
            } else {
                $encoded = $encoder->hashPassword($user, $passwords->getNewPassword());
                $user->setPassword($encoded);
                $em->persist($user);
                $em->flush();
                return $user;
            }
        } else {
            throw new BadRequestHttpException('user doesnt exists');
        }
    }
}
