<?php


namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Shop;
use App\Entity\Product;
use App\Entity\VariantLabel;
use App\Entity\VariantName;
use App\Model\Label;
use App\Model\Query;
use App\Model\Variant;
use App\Model\VariantProduct;
use App\Model\Variants;
use App\Service\UtilsService;
use App\Service\VariantService;
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

class VariantController implements ShopAuthentifiedController
{

    /**
     * @Post("/api/variant/query")
     * @ParamConverter(
    "query",
    class="App\Model\Query",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @return Json
     */
    public function queryVariant(Query $query, EntityManagerInterface $em, LoggerInterface $logger, TokenStorageInterface $tokenStorage)
    {
        $like = trim($query->getQuery());
        $ret = [];
        if(strlen($like)>1) {
            $variants = $em->getRepository(VariantLabel::class)->queryVariant($like);
            foreach($variants as $entity) {
                $ret[]  = UtilsService::mapFromTo($entity, new Label(), ['id' => 'id','label' => 'label']);
            }
        }
        return ['exists' => count($ret) > 0 , 'variants' => $ret ];
    }

    /**
     * @Patch("/api/product/{productUuid}/variant")
     * @ParamConverter(
    "variants",
    class="App\Model\Variants",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @return Variants
     */
    public function patchVariant(string $productUuid, Variants $variants, EntityManagerInterface $em, LoggerInterface $logger, VariantService $service)
    {
        $variants = $variants->getVariants();
        $entities = $em->getRepository(VariantName::class)->getVariantsForUuid($productUuid);
        foreach ($entities as $i => $variantNameEntity) {
            $tab = array_filter($variants, function ($elem) use ($variantNameEntity, $logger) {
                return ($elem->getId() == $variantNameEntity->getId());
            });

            $logger->error('here [tab]', [$tab]);
            $variantForVariantName = array_shift($tab);
            if ($variantForVariantName) {
                $entities[$i]->setName($variantForVariantName->getName());
                // on parcours tout les variant déja enregistré
                $removedVariantId = [];
                foreach ($entities[$i]->getVariantLabels() as $variantLabel) {
                    // on les compare a tout ceux founis
                    $existsLabelTab = array_filter($variantForVariantName->getLabels(), function ($label) use ($variantLabel, $logger) {
                        $logger->error('here id in ',  [$label->getId()]);
                        return $variantLabel->getId() == $label->getId();
                    });
                    // si il n'exist pas ils on été supprimé
                    // donc on les supprimes
                    $labelForVariantLabel = array_shift($existsLabelTab);
                    if (!$labelForVariantLabel) {

                        $removedVariantId[] = $variantLabel->getId();
                        $entities[$i]->removeVariantLabel($variantLabel);
                        $variantLabel->removeVariantName($entities[$i]);
                        $em->persist($variantLabel);
                        $em->flush();
                    }
                }
                // on doit recupérer tout ceux qui on été créée et ceux qui ne font pas partie des supprimés
                $addedLabels = array_filter($variantForVariantName->getLabels(), function ($elem) use ($removedVariantId) {
                    return !$elem->getId() || !in_array($elem->getId(), $removedVariantId);
                });
                foreach ($addedLabels as $label) {
                    if (!$label->getId()) {
                        $variantLabel = new VariantLabel();
                        $variantLabel->setLabel($label->getLabel());

                    } else {
                        $variantLabel = $em->getRepository(VariantLabel::class)->findOneById($label->getId());
                        // TODO : check why its removed.
                        /*
                        if(!$variantLabel) {
                            $variantLabel = new VariantLabel();
                            $variantLabel->setLabel($label->getLabel());
                        }
                        */

                    }
                    $variantLabel->addVariantName($entities[$i]);
                    $entities[$i]->addVariantLabel($variantLabel);
                    $em->persist($variantLabel);
                }
            } else {
                $logger->error('here [remove]');
                $em->remove($variantNameEntity);
            }
        }
        $addedVariants = array_filter($variants, function ($elem) {
            return !!!$elem->getId();
        });
        foreach ($addedVariants as $variant) {
            $variantName = new VariantName();
            $variantName->setProduct($em->getRepository(Product::class)->findOneByUuid($productUuid));
            $variantName->setName($variant->getName());
            $em->persist($variantName);
            foreach ($variant->getLabels() as $label) {
                if (!$label->getId()) {
                    $variantLabel = new VariantLabel();
                    $variantLabel->setLabel($label->getLabel());

                } else {
                    $logger->error('here', [$label->getId()]);
                    $variantLabel = $em->getRepository(VariantLabel::class)->findOneById($label->getId());
                    // TODO : check why its removed.
                    /*
                    if(!$variantLabel) {
                        $variantLabel = new VariantLabel();
                        $variantLabel->setLabel($label->getLabel());
                    }
                    */
                }
                $variantLabel->addVariantName($variantName);
                $variantName->addVariantLabel($variantLabel);
                $em->persist($variantLabel);
            }
        }
        $em->flush();
        $ret = $service->getVariantsFromProductUuid($productUuid);
        $service->generateProductVariant($ret, $productUuid);
        return $ret;
    }

    /**
     * @Get("/api/product/{productUuid}/variant")
     * @View( serializerGroups={"output"})
     * @return Variants
     */
    public function getVariants(string $productUuid, EntityManagerInterface $em, LoggerInterface $logger, VariantService $service)
    {
        return $service->getVariantsFromProductUuid($productUuid);
    }

    /**
     * @Get("/api/variant-product")
     * @View( serializerGroups={"pv"})
     * @return array
     */
    public function getVariantProducts(LoggerInterface $logger, VariantService $service)
    {
        return $service->getVariantProducts(0, 20);
    }

    /**
     * @Get("/api/variant-product/{uuid}")
     * @View( serializerGroups={"pv"})
     * @return array
     */
    public function getVariantProduct($uuid, VariantService $service )
    {
        return $service->getVariantProduct($uuid);
    }

    /**
     * @Patch("/api/variant-product")
     * @ParamConverter(
    "variantProduct",
    class="App\Model\VariantProduct",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"pv"} } }
    )
     * @View( serializerGroups={"pv"})
     * @return VariantProduct
     */
    public function patchVariantProduct(VariantProduct $variantProduct, VariantService $service, EntityManagerInterface $em, LoggerInterface $logger) {
       $entity = $em->getRepository(\App\Entity\VariantProduct::class)->getOneByUuid($variantProduct->getId());
       if(!$entity) {
           throw new BadRequestHttpException('Variant Product doesn t exists');
       }
       $entity = UtilsService::mapFromTo($variantProduct, $entity , ['label'=> 'label', 'price' => 'price']);
       // picture case
        // on cerche les image qui on été rajoutés.
        // parmis tout les variant produit quel sont les image qui n'existe pas.
        // complementaire.
        $sendPictures = $variantProduct->getPictures()->toArray();
        $pictureToRemove = [];
        $logger->error('here [picture]', [count($variantProduct->getPictures())]);
        foreach($entity->getPictures() as $storedPicture ) {
            $tab = array_filter($sendPictures, function($pic) use($storedPicture, $logger) {
                $logger->error('here [pic]', [$pic]);
                $logger->error('here [stored]', [$storedPicture]);
                $pic->getId() === $storedPicture->getId() && $pic->getFile() === $storedPicture->getFile();
            });
            if(count($tab) === 0) {
                $pictureToRemove[] = $storedPicture;
            }
        }
        $pictureToAdd = [];
        foreach($sendPictures as $sendPicture) {
            $tab = array_filter($entity->getPictures()->toArray(), function($pic) use ($sendPicture) {
                $pic->getId() === $sendPicture->getId() && $pic->getFile() === $sendPicture->getFile();
            });
            if(count($tab) === 0) {
                $pictureToAdd[] = $sendPicture;
            }
        }
        $logger->error('here [pictureTo Add ]', $pictureToAdd);
        foreach($pictureToRemove as $toRemovePicture) {
            $entity->removePicture($toRemovePicture);
            $em->remove($toRemovePicture);
        }
        foreach($pictureToAdd as $toAddPicture) {
            $pic = new Picture();
            $pic->setFile($toAddPicture->getFile());
            $entity->addPicture($pic);
            $em->persist($pic);
        }
        $em->persist($entity);
        $em->flush();
        return $service->getVariantProduct($variantProduct->getId());

        // parmis toute les image quelle sont celle qui ne sont plus dans le variant produt
    }

}