<?php


namespace App\Service;


use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\VariantLabel;
use App\Entity\VariantName;
use App\Entity\VariantProduct;
use App\Model\Label;
use App\Model\Variant;
use App\Model\Variants;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class VariantService
{
    private $em;
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger) {
        $this->logger = $logger;
        $this->em = $em;
    }

    public function getVariantsFromProductUuid($uuid): Variants {
        $entities = $this->em->getRepository(VariantName::class)->getVariantsForUuid($uuid);
        $ret = new Variants();
        foreach($entities as $entity) {
            $variant = new Variant();
            $variant->setName($entity->getName());
            $variant->setId($entity->getId());
            foreach($entity->getVariantLabels() as $variantLabel) {
                $label = new Label();
                $label->setId($variantLabel->getId());
                $label->setLabel($variantLabel->getLabel());
                $variant->addLabel($label);
            }
            $ret->addVariant($variant);
        }
        return $ret;
    }

    public function generateProductVariant(Variants $variants, $uuid) {
        $res = $variants->getVariants();

        // TODO query to get pictures.
        $variantProducts = $this
            ->em
            ->getRepository(VariantProduct::class)
            ->getFromProductUuid($uuid);


        $product = $this
            ->em
            ->getRepository(Product::class)
            ->findOneByUuid($uuid);

        $this->onProcess($variants, $variantProducts, $product);

    }

    private function onProcess(Variants $variants, $variantProducts, Product $product) {
        $complementaire = [];
        $this->isVariantStrictlyIncludeInVariantProduct($variants,$variantProducts,$res, $complementaire);
        // vert rouge | 100 200, on rajoute 300 ...
        // si on rajoute 1L
        foreach($complementaire as $tuple) {
            $sizeMax = 0;
            $toCopy = null;
            foreach($res as $row) {
                if(UtilsService::contains($row['tuple'], $tuple)) {
                    if($sizeMax<count($tuple)) {
                        $sizeMax = count($tuple);
                        $toCopy = array_shift($row['variantProducts']);
                    }
                }
            }
            $mapping = join('#', $tuple);
            $variantProduct = new VariantProduct();
            if ($toCopy) {
                $variantProduct = $this->copyVariantProduct($toCopy, $variantProduct);
            }
            $variantProduct->setProduct($product);
            $variantProduct->setVariantMapping($mapping);
            $this->em->persist($variantProduct);
            $this->em->flush();
        }
        foreach($variantProducts as $variantProduct) {
            if (!$this->isVariantProductIncludeInVariants($variantProduct, $variants)) {
                $this->em->remove($variantProduct);
            }
            $this->em->flush();
        }
    }

    private function copyVariantProduct(VariantProduct $from, VariantProduct $to): VariantProduct {
        // price
        // label
        // picture
        $to->setPrice($from->getPrice());
        $to->setLabel($from->getLabel());
        $pictures = $from->getPictures();
        foreach($pictures as $picture) {
            $newPic = new Picture();
            $newPic->setFile($picture->getFile());
            $newPic->setVariantProduct($to);
            $this->em->persist($newPic);
        }
        return $to;
    }

    private function isVariantStrictlyIncludeInVariantProduct(Variants $variants, $variantProducts , &$res, &$complementaire) {
        $variantTuples = $this->getTupleFromVariants($variants);
        $ret = true;
        $complementaire = [];
        $res = [];
        foreach($variantTuples as $variantTuple) {
            $includedProductVariants = [];
            $isInclude = $this->tupleIsIncludeInVariantProducts($variantTuple, $variantProducts, $includedProductVariants);
            $res[] = ['tuple' => $variantTuple, 'isInclude' => $isInclude, 'variantProducts' => $includedProductVariants];
            if(!$isInclude) {
                $ret = false;
                $complementaire[] = $variantTuple;

            }
        }
        return $ret;
    }

    private function isVariantProductIncludeInVariants(VariantProduct $variantProduct, Variants $variants) {
        $tuples = $this->getTupleFromVariants($variants);
        $ret = false;
        $tupleToMatch = explode('#', $variantProduct->getVariantMapping());
        foreach($tuples as $tuple) {
            if (UtilsService::contains($tupleToMatch, $tuple) && UtilsService::contains($tuple, $tupleToMatch)) {
                $ret = true;
            }

        }
        return $ret;
    }

    private function getTupleFromVariants(Variants $variants) : array {
        $variants = $variants->getVariants();
        $res = [];
        foreach($variants as $variant) {
            $ret= [];
            foreach($variant->getLabels() as $label) {
                $ret[] = $variant->getId() . '_' . $label->getId();
            }
            $res[] = $ret;
        }
        // TODO reorder associationCombination.
        $this->logger->error('here [bug]', $res);
        return UtilsService::associations($res);
    }


    // vert 200g include in vert 200g 1L et dans vert 200g 2 L
    // si il est strictement inclus ... on ne retourne pas
    // on prende le cas
    // vert rouge
    // 200g 300g auquel on rajoute 400g
    // vert 400 g pas inclus dans vert 200g => pas inclus : on ajoute au complementaire
    // 400g inclus dans rien => on ajoute au complémenatire
    // vert inclus dans vert 200g => on n'ajoute pas au complementaire, on n'est pas supposé retourné le PV
    // vert 300g inclus dans vert 300g => on ne retourne pas le PV
    // on ne retourne que les PV qu'on est censé effacé cad uniquement si il y a reduction

    // si on va dans l'autre sens : suppresion du 400 g
    // on supprime 400, 400 vert , 400 rouge
    // tuple include in nothing
    // on parcours les PV et ceux qui n'ont pas de mapping on les supprime
    private function tupleIsIncludeInVariantProducts($tuple, $productVariants, &$ret) {
        $ret = array_filter($productVariants, function (VariantProduct $elem) use ($tuple) {
            $mapping = $elem->getVariantMapping();
            $productVariantTuple = explode('#', $mapping);
            return UtilsService::contains($tuple, $productVariantTuple) && UtilsService::contains($productVariantTuple, $tuple);

        });
        return count($ret) > 0;
    }

    public function getVariantProducts($page= 0, $perPage = 20) {
        $vps = $this->em->getRepository(VariantProduct::class)->getAll($page, $perPage);
        $ret= [];
        foreach($vps as $vp) {
            $variantProduct = new \App\Model\VariantProduct();
            $variantProduct->setPictures($vp->getPictures());
            $labels = $this->em->getRepository(VariantLabel::class)->getLabelsFromVariantMapping($vp->getVariantMapping(), $this->logger);
            $variantProduct->setLabels($labels);
            $variantProduct->setPrice($vp->getPrice());
            $variantProduct->setLabel($vp->getLabel());
            $variantProduct->setProduct($vp->getProduct());
            $variantProduct->setId($vp->getId());
            $ret[] = $variantProduct;
        }
        return $ret;
    }

}
