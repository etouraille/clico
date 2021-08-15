<?php


namespace App\Controller\Front;

use App\Entity\Product;
use App\Entity\VariantLabel;
use App\Entity\VariantRemoved;
use App\Model\Address;
use App\Model\Shop;
use App\Model\VariantProduct;
use App\Service\UtilsService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductController
{

    /**
     * @Get("/product")
     * @View( serializerGroups={"product"})
     */
    public function getProduct(Request $request, EntityManagerInterface $em, LoggerInterface $logger) {
        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');

       $products =  $em->getRepository(Product::class)->getProductsArround($lat, $lng);
       return $products;

    }

    /**
     * @Get("/product/{uuid}")
     * @View( serializerGroups={"output"})
     */
    public function getOneProduct($uuid, EntityManagerInterface $em, LoggerInterface $logger) {
        $product = $em->getRepository(Product::class)->findOneByUuidWithVariant($uuid);
        $variantsRemoved = $em->getRepository(VariantRemoved::class)->findByProduct($uuid);
        $variantMappingRemoved = array_map(function($elem) {
            return explode('#', $elem->getVariantMapping());
        }, $variantsRemoved);
        $variantProduts = $product->getVariantProducts();
        $variantProductsRemain = new ArrayCollection();
        foreach($variantProduts as $vp) {
            $tab = array_filter($variantMappingRemoved, function ($elem) use($vp) {
                return UtilsService::contains(explode('#',$vp->getVariantMapping()), $elem);
            });
            if(count($tab) === 0) {
                $model = new VariantProduct();
                // TODO change ici si on ajoute des propriétés au variant Product.
                $model
                    ->setPrice($vp->getPrice())
                    ->setLabel($vp->getLabel())
                    ->setLabels($em->getRepository(VariantLabel::class)->getLabelsFromVariantMapping($vp->getVariantMapping()))
                    ->setPictures($vp->getPictures())
                ;
                $variantProductsRemain->add($model);
            }

        }
        return $product->setVariantProducts($variantProductsRemain);

    }
}
