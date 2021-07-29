<?php


namespace App\EventListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Ramsey\Uuid\Uuid;


use App\Entity\Indentifiable;

class AddUuidListener
{
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Indentifiable) {
            return;
        }

        $em = $args->getObjectManager();
        $entity->setUuid(Uuid::uuid4()->toString());
        $em->persist($entity);
        $em->flush();
    }
}
