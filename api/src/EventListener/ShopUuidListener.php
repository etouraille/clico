<?php


namespace App\EventListener;


use App\Controller\ShopAuthentifiedController;
use App\Entity\Shop;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ShopUuidListener
{

    private $em;
    private $tokenStorage;
    private $logger;




    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, LoggerInterface $logger) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }




    public function onKernelController(ControllerEvent  $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof ShopAuthentifiedController) {
            $this->logger->error('here [                       ]');
            $header = $event->getRequest()->headers->get('Shop');
            preg_match('#Shop (.*)$#', $header, $match);
            if ($header && $uuid = $match[1]) {
                $shop = $this->em->getRepository(Shop::class)->findOneByUuidAndUser($uuid, $this->tokenStorage->getToken()->getUser());
            }
            if (!$shop) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            } else {
                 $event->getRequest()->attributes->set('shop', $shop);
            }
        }
    }


    /*
    public static function getSubscribedEvents()
    {
        return [
            KernelEvent::CONTROLLER => 'onKernelController',
        ];
    }
    */

}
