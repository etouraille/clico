<?php


namespace App\Model;


use App\Entity\Picture;
use Doctrine\Common\Collections\ArrayCollection;

class VariantProduct
{
    private $id;
    private $labels;
    private $pictures;
    private $price;
    private $label;
    private $product;
    private $removed;
    private $variantMapping;

    /**
     * @return mixed
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * @param mixed $removed
     */
    public function setRemoved($removed): void
    {
        $this->removed = $removed;
    }

    /**
     * @return mixed
     */
    public function getVariantMapping()
    {
        return $this->variantMapping;
    }

    /**
     * @param mixed $variantMapping
     */
    public function setVariantMapping($variantMapping): void
    {
        $this->variantMapping = $variantMapping;
    }


    public function __construct() {
        // $this->pictures = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param mixed $labels
     */
    public function setLabels($labels): self
    {
        $this->labels = $labels;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param mixed $pictures
     */
    public function setPictures($pictures): self
    {
        $this->pictures = $pictures;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): self
    {
        $this->label = $label;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    public function addPicture(Picture $picture) {
        $this->pictures->add($picture);
    }


}
