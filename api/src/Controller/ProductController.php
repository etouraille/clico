<?php


namespace App\Controller;

use App\Entity\Shop;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductController
{

    /**
     * @Post("/api/product")
     * @ParamConverter(
    "product",
    class="App\Entity\Product",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @return Product
     */
    public function createProduct(Product $product, EntityManagerInterface $em, LoggerInterface $logger, TokenStorageInterface $tokenStorage)
    {
        $shop = $em->getRepository(Shop::class)->findOneByUuid($product->getShopUuid());
        $product->setShop($shop);
        foreach($product->getPictures() as $picture) {
            $picture->setProduct($product);
        }
        $em->persist($product);
        $em->flush();
        return $product;
    }

    /**
     * @Patch("/api/shop/{uuid}/product")
     * @ParamConverter(
    "product",
    class="App\Entity\Product",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View(serializerGroups={"output"})
     * @return Product
     */
    public function patchProduct(Product $product, $uuid, EntityManagerInterface $em, LoggerInterface $logger, TokenStorageInterface $tokenStorage)
    {
        $shop = $em->getRepository(Shop::class)->findOneByUuid($uuid);
        $entity = $em->getRepository(Product::class)->findOneByUuid($product->getUuid());
        $product->setId($entity->getId());
        $product->setShop($shop);
        foreach($product->getPictures() as $picture) {
            $picture->setProduct($product);
        }
        $em->merge($product);
        $em->flush();
        return $product;
    }

    /**
     * @Get("/api/shop/{shopUuid}/product")
     * @View( serializerGroups={"output"})
     * @return page, data => Product[]
     */
    public function getProducts($shopUuid, EntityManagerInterface $em, LoggerInterface $logger, TokenStorageInterface $tokenStorage, Request $request)
    {
        return $em
            ->getRepository(Product::class)
            ->getProducstByShopUuid($shopUuid);
        /*
            ->getProductByShopUuidAndFilterPaginate(
                $shopUuid,
                $request->query->get('filter',''),
                $request->query->get('orderBy', 'ASC'),
                $request->query->get('pageNumber', 0),
                $request->query->get('pageSize', 10 )
            );
        */
    }

    /**
     * @Delete("/api/product/{uuid}")
     * @View( serializerGroups={"output"})
     * @return Product
     */
    public function deleteProduct($uuid, EntityManagerInterface $em, LoggerInterface $logger, TokenStorageInterface $tokenStorage)
    {
        $entity = $em->getRepository(Product::class)->findOneByUuid($uuid);
        $em->remove($entity);
        $em->flush();
        return $entity;
    }


}
