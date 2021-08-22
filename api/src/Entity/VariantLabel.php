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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank;

    /**
     * @ORM\ManyToOne(targetEntity=VariantName::class, inversedBy="variantLabels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $variantName;

    public function __construct()
    {

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

    
    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getVariantName(): ?VariantName
    {
        return $this->variantName;
    }

    public function setVariantName(?VariantName $variantName): self
    {
        $this->variantName = $variantName;

        return $this;
    }
}
