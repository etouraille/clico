<?php


namespace App\Controller;

use App\Model\Address;
use App\Model\Shop;
use App\Service\UtilsService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopController
{
    const fromModelToEntity = [
        'name' => 'name',
        'type' => 'type',
        'address.address' => 'address',
        'address.lat' => 'lat',
        'address.lng' => 'lng',
        'file' => 'file',
        'email' => 'email',
        'phone' => 'phone',
        'mobile' => 'mobile',
    ];

    const fromEntityToModel = [
        'uuid' => 'uuid',
        'name' => 'name',
        'type' => 'type',
        'address' => 'address.address',
        'lat' => 'address.lat',
        'lng' => 'address.lng',
        'file' => 'file',
        'email' => 'email',
        'phone' => 'phone',
        'mobile' => 'mobile',
    ];
    /**
     * @Post("/api/create-shop")
     * @ParamConverter(
    "shop",
    class="App\Model\Shop",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @param Subscribe $subscribe
     * @return producttepOnes
     */
    public function createShop(Shop $shop, EntityManagerInterface $em, LoggerInterface $logger, TokenStorageInterface $tokenStorage )
    {
        $entity = UtilsService::mapFromTo($shop, new \App\Entity\Shop(), self::fromModelToEntity);
        $user = $tokenStorage->getToken()->getUser();
        $entity->setOwner($user);
        $em->persist($entity);
        $em->flush();

        return ['uuid' => $entity->getUuid()->toString()];
    }

    /**
     * @Get("/api/shop/{uuid}")
     * @View( serializerGroups={"output"})
     * @param string $uuid
     * @param EntityManagerInterface $em
     * @return Shop
     */

    public function getShop(string $uuid, EntityManagerInterface $em, LoggerInterface $logger): Shop
    {
        $logger->error('here');
        $shop = $em->getRepository(\App\Entity\Shop::class)
            ->findOneByUuid($uuid);

        if ($shop instanceof \App\Entity\Shop) {
            return UtilsService::mapFromTo($shop, new Shop(), self::fromEntityToModel);
        } else {
            throw new BadRequestHttpException('The shop does not exists');
        }
    }

    /**
     * @Patch("/api/shop")
     * @ParamConverter(
    "shop",
    class="App\Model\Shop",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @param Shop $shop
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @return Shop
     */
    public function patchShop(Shop $shop, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $entity = $em->getRepository(\App\Entity\Shop::class)
            ->findOneByUuid($shop->getUuid());

        if (!$entity instanceof \App\Entity\Shop) {
            throw new BadRequestHttpException('Shop does not exists');
        }

        $entity = UtilsService::mapFromTo($shop, $entity, self::fromModelToEntity);
        $em->persist($entity);
        $em->flush();

        return UtilsService::mapFromTo($entity, new Shop(), self::fromEntityToModel);


    }

    /**
     * @Get("/api/shops")
     * @View( serializerGroups={"output"})
     * @param EntityManagerInterface $em
     * @return Shop
     */

    public function getUserShops(TokenStorageInterface $tokenStorage, EntityManagerInterface $em) : array {
        $user = $tokenStorage->getToken()->getUser();
        $shops = $em->getRepository(\App\Entity\Shop::class)->getByOwner($user);
        $ret = [];
        foreach( $shops as $shop ) {
            $ret[] = UtilsService::mapFromTo($shop, new Shop(), self::fromEntityToModel);
        }
        return $ret;
    }
}
