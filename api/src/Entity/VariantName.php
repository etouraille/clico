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
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="variantNames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=VariantLabel::class, mappedBy="variantName", cascade={"remove","persist"})
     */
    private $variantLabels;

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

    public function __construct()
    {
        $this->variantLabels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $variantLabel->addVariantName($this);
        }

        return $this;
    }

    public function removeVariantLabel(VariantLabel $variantLabel): self
    {
        if ($this->variantLabels->removeElement($variantLabel)) {
            $variantLabel->removeVariantName($this);
        }

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
}
