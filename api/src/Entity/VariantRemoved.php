<?php

namespace App\Entity;

use App\Repository\VariantRemovedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariantRemovedRepository::class)
 */
class VariantRemoved
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="variantsRemoved")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $variantMapping;

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

    public function getVariantMapping(): ?string
    {
        return $this->variantMapping;
    }

    public function setVariantMapping(string $variantMapping): self
    {
        $this->variantMapping = $variantMapping;

        return $this;
    }
}
