<?php

namespace App\Entity;

use App\Repository\VariantProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariantProductRepository::class)
 */
class VariantProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="variantProduct")
     */
    private $pictures;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;





    /**
     * @ORM\Column(type="string", length=255)
     */
    private $variantMapping;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="variantProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setVariantProduct($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getVariantProduct() === $this) {
                $picture->setVariantProduct(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }


    public function getVariantMapping(): ?string
    {
        return $this->variantMapping;
    }

    public function setVariantMapping(string $variantMapping): self
    {
        $this->variantMapping = $variantMapping;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
