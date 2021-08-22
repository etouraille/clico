<?php

namespace App\Entity;

use App\Repository\VariantNameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariantNameRepository::class)
 */
class VariantName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="variantNames")
     */
    private $shop;

    /**
     * @ORM\OneToMany(targetEntity=VariantLabel::class, mappedBy="variantName", orphanRemoval=true)
     */
    private $variantLabels;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="variantNames")
     */
    private $products;

    public function __construct()
    {
        $this->variantLabels = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }



    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return Collection|VariantLabel[]
     */
    public function getVariantLabels(): Collection
    {
        return $this->variantLabels;
    }

    public function addVariantLabel(VariantLabel $variantLabel): self
    {
        if (!$this->variantLabels->contains($variantLabel)) {
            $this->variantLabels[] = $variantLabel;
            $variantLabel->setVariantName($this);
        }

        return $this;
    }

    public function removeVariantLabel(VariantLabel $variantLabel): self
    {
        if ($this->variantLabels->removeElement($variantLabel)) {
            // set the owning side to null (unless already changed)
            if ($variantLabel->getVariantName() === $this) {
                $variantLabel->setVariantName(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
