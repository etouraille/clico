<?php

namespace App\Entity;

use App\Repository\VariantLabelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariantLabelRepository::class)
 */
class VariantLabel
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
    private $label;


    /**
     * @ORM\ManyToMany(targetEntity=VariantName::class, inversedBy="variantLabels")
     */
    private $variantName;

    public function __construct()
    {
        $this->variantName = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|VariantName[]
     */
    public function getVariantName(): Collection
    {
        return $this->variantName;
    }

    public function addVariantName(VariantName $variantName): self
    {
        if (!$this->variantName->contains($variantName)) {
            $this->variantName[] = $variantName;
        }

        return $this;
    }

    public function removeVariantName(VariantName $variantName): self
    {
        $this->variantName->removeElement($variantName);

        return $this;
    }
}
