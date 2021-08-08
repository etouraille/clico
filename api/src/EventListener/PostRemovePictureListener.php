<?php


namespace App\EventListener;

use App\Entity\Picture;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class PostRemovePictureListener
{
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Picture) {
            return;
        }

        /*
        $file = $entity->getFile();
        $url = "http://cdn/picture/" . $file;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($curl);

        $this->logger->error('here', [$resp]);

        curl_close($curl);

        $em = $args->getObjectManager();
        */
    }
}

