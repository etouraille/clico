<?php


namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Shop;
use App\Entity\Product;
use App\Entity\VariantLabel;
use App\Entity\VariantName;
use App\Entity\VariantRemoved;
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
        if (strlen($like) > 1) {
            $variants = $em->getRepository(VariantLabel::class)->queryVariant($like);
            foreach ($variants as $entity) {
                $ret[] = UtilsService::mapFromTo($entity, new Label(), ['id' => 'id', 'label' => 'label']);
            }
        }
        return ['exists' => count($ret) > 0, 'variants' => $ret];
    }


    /**
     * @Post("/api/variant-name/query")
     * @View( serializerGroups={"output"})
     * @return array
     */
    public function queryVariantName(Request $request, Shop $shop, VariantService $service): Variants
    {
        $query = json_decode($request->getContent(), true)['query'];
        $ret = [];
        if($query && strlen($query) > 1) {
            return $service->getVariantsForShop($shop, $query);
        }
        return new Variants();

    }


    /**
     * @Patch("/api/variant")
     * @ParamConverter(
    "variants",
    class="App\Model\Variants",
    converter="fos_rest.request_body",
    options={"deserializationContext"={"groups"={"input"} } }
    )
     * @View( serializerGroups={"output"})
     * @return Variants
     */
    public function patchVariant(Shop $shop, Variants $variants, EntityManagerInterface $em, LoggerInterface $logger, VariantService $service)
    {
        $removedVariantMapping = [];
        $variants = $variants->getVariants();
        $entities = $em->getRepository(VariantName::class)->getVariantsForShop($shop);
        foreach ($entities as $i => $variantNameEntity) {
            $tab = array_filter($variants, function ($elem) use ($variantNameEntity, $logger) {
                return ($elem->getId() == $variantNameEntity->getId());
            });

            $variantForVariantName = array_shift($tab);
            if ($variantForVariantName) {
                $entities[$i]->setName($variantForVariantName->getName());
                $entities[$i]->setRank($variantForVariantName->getRank());
                $entities[$i]->setType($variantForVariantName->getType());
                $em->persist($entities[$i]);
                $em->flush();
                // on parcours tout les variant déja enregistré
                $removedVariantId = [];
                foreach ($entities[$i]->getVariantLabels() as $variantLabel) {
                    // on les compare a tout ceux fournis
                    $existsLabelTab = array_filter($variantForVariantName->getLabels(), function ($label) use ($variantLabel, $logger) {
                        return $variantLabel->getId() == $label->getId();
                    });
                    // si il n'exist pas ils on été supprimé
                    // donc on les supprimes
                    $labelForVariantLabel = array_shift($existsLabelTab);
                    if (!$labelForVariantLabel) {

                        $removedVariantId[] = $variantLabel->getId();
                        $entities[$i]->removeVariantLabel($variantLabel);
                        $em->remove($variantLabel);
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
                        $variantLabel->setRank($label->getRank());

                    } else {
                        $variantLabel = $em->getRepository(VariantLabel::class)->findOneById($label->getId());
                    }
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
            $variantName->setShop($shop);
            $variantName->setName($variant->getName());
            $variantName->setRank($variant->getRank());
            $variantName->setType($variant->getType());
            $em->persist($variantName);
            foreach ($variant->getLabels() as $label) {
                if (!$label->getId()) {
                    $variantLabel = new VariantLabel();
                    $variantLabel->setLabel($label->getLabel());
                    $variantLabel->setRank($label->getRank());

                } else {
                    $logger->error('here', [$label->getId()]);
                    $variantLabel = $em->getRepository(VariantLabel::class)->findOneById($label->getId());

                }
                $variantName->addVariantLabel($variantLabel);
                $em->persist($variantLabel);
            }
        }
        $em->flush();
        $ret = $service->getVariantsForShop($shop);

        return $ret;
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
    public function patchVariantForProduct(string $productUuid, Variants $variants, EntityManagerInterface $em, LoggerInterface $logger, VariantService $service)
    {
        $removedVariantMapping = [];
        $_variants = $variants->getVariants();
        $product = $em->getRepository(Product::class)->findOneByUuid($productUuid);
        $entities = $em->getRepository(VariantName::class)->getVariantsForUuid($productUuid);
        foreach ($entities as $i => $variantNameEntity) {
            $tab = array_filter($_variants, function ($elem) use ($variantNameEntity, $logger) {
                return ($elem->getId() == $variantNameEntity->getId());
            });

            $variantForVariantName = array_shift($tab);
            if (!$variantForVariantName) {
                $con = $em->getConnection();
                $con->executeStatement('
                                DELETE FROM variant_name_product 
                                WHERE variant_name_id = :variant_name_id 
                                AND product_id = :product_id', [
                    'variant_name_id' => $variantNameEntity->getId(),
                    'product_id' => $product->getId()
                ]);

            }
        }
        $addedVariants = array_filter($_variants, function ($elem) {
            return $elem->getId();
        });
        foreach ($addedVariants as $variant) {
            $con = $em->getConnection();
            try {
                $con->executeStatement('
                                INSERT INTO variant_name_product 
                                ( variant_name_id, product_id) 
                                VALUES (:variant_name_id,  :product_id )', [
                    'variant_name_id' => $variant->getId(),
                    'product_id' => $product->getId()
                ]);
            } catch(\Exception $e) {

            }
        }
        $em->flush();
        $service->generateProductVariant($variants, $productUuid);

        return $variants;
    }

    /**
     * @Get("/api/product/{productUuid}/variant")
     * @View( serializerGroups={"output"})
     * @return Variants
     */

    public function getVariantsForProduct($productUuid, VariantService $service ) {
        return $service->getVariantsForProductUuid($productUuid);
    }


    /**
     * @Get("/api/variant")
     * @View( serializerGroups={"output"})
     * @return Variants
     */
    public function getVariants(Shop $shop, VariantService $service)
    {
        return $service->getVariantsForShop($shop);
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
    public function getVariantProduct($uuid, VariantService $service)
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
    public function patchVariantProduct(VariantProduct $variantProduct, VariantService $service, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $entity = $em->getRepository(\App\Entity\VariantProduct::class)->getOneByUuid($variantProduct->getId());
        if (!$entity) {
            throw new BadRequestHttpException('Variant Product doesn t exists');
        }
        $entity = UtilsService::mapFromTo($variantProduct, $entity, ['label' => 'label', 'price' => 'price']);
        // picture case
        // on cerche les image qui on été rajoutés.
        // parmis tout les variant produit quel sont les image qui n'existe pas.
        // complementaire.
        $sendPictures = $variantProduct->getPictures()->toArray();
        $pictureToRemove = [];
        foreach ($entity->getPictures() as $storedPicture) {
            $tab = array_filter($sendPictures, function ($pic) use ($storedPicture, $logger) {
                $pic->getId() === $storedPicture->getId() && $pic->getFile() === $storedPicture->getFile();
            });
            if (count($tab) === 0) {
                $pictureToRemove[] = $storedPicture;
            }
        }
        $pictureToAdd = [];
        foreach ($sendPictures as $sendPicture) {
            $tab = array_filter($entity->getPictures()->toArray(), function ($pic) use ($sendPicture) {
                $pic->getId() === $sendPicture->getId() && $pic->getFile() === $sendPicture->getFile();
            });
            if (count($tab) === 0) {
                $pictureToAdd[] = $sendPicture;
            }
        }
        foreach ($pictureToRemove as $toRemovePicture) {
            $entity->removePicture($toRemovePicture);
            $em->remove($toRemovePicture);
        }
        foreach ($pictureToAdd as $toAddPicture) {
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

    /**
     * @Patch("/api/product/{uuid}/variant-removed")
     * @return array
     */
    public function patchVariantRemoved($uuid, Request $request, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $product = $em->getRepository(Product::class)->findOneByUuid($uuid);
        if(!$product) {
            throw new BadRequestHttpException('The product doesn t exists');
        }
        $variantsRemoved = $em->getRepository(VariantRemoved::class)->findByProduct($uuid);
        foreach($variantsRemoved as $variantRemoved ) {
            $em->remove($variantRemoved);
        }
        $em->flush();
        $data = json_decode($request->getContent(), true);
        foreach($data as $variantMapping ) {
            $vr = new VariantRemoved();
            $vr->setProduct($product);
            $vr->setVariantMapping($variantMapping);
            $em->persist($vr);
        }
        $em->flush();
        return $data;
    }

    // TOGGLE VARIANT REMOVED
    /**
     * @Post("/api/product/{uuid}/variant-removed")
     * @return array
     */
    public function toggleVariantRemoved($uuid, Request $request, EntityManagerInterface $em, LoggerInterface $logger, VariantService $service)
    {
        $product = $em->getRepository(Product::class)->findOneByUuid($uuid);
        $data = json_decode($request->getContent(), true);
        $key = explode('#', $data['variantMapping']);
        if(!$product) {
            throw new BadRequestHttpException('The product doesn t exists');
        }
        $variantsRemoved = $em->getRepository(VariantRemoved::class)->findByProduct($uuid);
        $tab = [];
        $toBeMaybeDeleted = [];
        foreach($variantsRemoved as $variantRemoved ) {
            $tab[$variantRemoved->getId()] = explode('#',$variantRemoved->getVariantMapping());
            $toBeMaybeDeleted[$variantRemoved->getId()] = $variantRemoved;


        }
        // le complementaire de key est affecté par defaut dans la liste des produit
        $filtered  = $service->getFilteredVariantProductMapping($key , $product);
        $logger->error('here[filtered]', $filtered);
        $filtered = array_filter($filtered, function( $elem ) use ($tab) {
            $tab = array_filter($tab, function($some) use($elem) {
               return UtilsService::contains($elem, $some) && UtilsService::contains($some, $elem);
            });
            if(count($tab) > 0) {
                return false;
            }
            return true;
        });



        if ($data['add']) {
            // add case.
            foreach($tab as $vrIndex => $a ) {
                if( UtilsService::contains($a, $key) && UtilsService::contains($key, $a)) {
                    if(!preg_match('#comp_#',$vrIndex)) $em->remove($toBeMaybeDeleted[$vrIndex]);
                    $em->flush();
                } elseif(UtilsService::contains($key, $a)) {
                    // supprimer celui ci
                    // ajouter les complementaires.
                    // on le complementaire de $key contains $a c'et
                    // exemple rouge 100g  1L contains rouge
                    // on ajoute rouge 200g , rouge 300g
                    $complemenataires = $service->getComplementaires($key, $a, $product->getUuid());
                    if(!preg_match('#comp_#',$vrIndex)) $em->remove($toBeMaybeDeleted[$vrIndex]);
                    $logger->error('here[comp]', [$complemenataires]);
                    foreach($complemenataires as $comp ) {
                        $vr = new VariantRemoved();
                        $vr->setVariantMapping(implode('#', $comp));
                        $vr->setProduct($product);
                        $em->persist($vr);
                    }
                    $em->flush();

                }
            }
            foreach($filtered as $key ) {
                $vr = new VariantRemoved();
                $vr->setVariantMapping(implode('#', $key));
                $vr->setProduct($product);
                $em->persist($vr);
                $em->flush();
            }
        } else {
            // remove case. ( remove product )
            // rouge 100g 1L
            $vr = new VariantRemoved();
            $vr->setProduct($product);
            $vr->setVariantMapping(implode('#', $key));
            $em->persist($vr);
            $em->flush();

        }
        return $data;
    }


    /**
     * @Get("/api/product/{uuid}/variant-removed")
     * @return array
     */
    public function getVariantRemoved($uuid, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $product = $em->getRepository(Product::class)->findOneByUuid($uuid);
        if(!$product) {
            throw new BadRequestHttpException('The product doesn t exists');
        }
        $variantsRemoved = $em->getRepository(VariantRemoved::class)->findByProduct($uuid);
        $data = [];
        foreach($variantsRemoved as $variantRemoved ) {
            $data[] = $variantRemoved->getVariantMapping();
        }
        return $data;
    }
}
