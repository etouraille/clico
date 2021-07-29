<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class UtilsController
{

    /**
     * @Route("/emailExists/{email}", name="emailExists_route")
     */
    public function emailExists(string $email, EntityManagerInterface $em): Array {
        $user = $em->getRepository(User::class)->findOneByEmail($email);
        if ($user instanceof User) {
            return ['exists' => true ];
        } else {
            return ['exists' => false];
        }
    }
}
