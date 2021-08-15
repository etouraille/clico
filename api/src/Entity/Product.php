<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product implements Indentifiable
{
    use EntityIdTrait;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="product",cascade={"persist","remove"})
     */
    private $pictures;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    private $shopUuid;

    /**
     * @ORM\OneToMany(targetEntity=VariantName::class, mappedBy="product", orphanRemoval=true)
     */
    private $variantNames;

    /**
     * @ORM\OneToMany(targetEntity=VariantProduct::class, mappedBy="product", orphanRemoval=false)
     */
    private $variantProducts;

    /**
     * @ORM\OneToMany(targetEntity=VariantRemoved::class, mappedBy="product", orphanRemoval=true)
     */
    private $variantsRemoved;



    /**
     * @return mixed
     */
    public function getShopUuid()
    {
        return $this->shopUuid;
    }

    /**
     * @param mixed $shopUuid
     */
    public function setShopUuid($shopUuid): void
    {
        $this->shopUuid = $shopUuid;
    }


    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->variantNames = new ArrayCollection();
        $this->variantProducts = new ArrayCollection();
        $this->variantsRemoved = new ArrayCollection();
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            //$this->pictures[] = $picture;
            $this->pictures->add($picture);
            $picture->setProduct($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProduct() === $this) {
                $picture->setProduct(null);
            }
        }

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
     * @return Collection|VariantName[]
     */
    public function getVariantNames(): Collection
    {
        return $this->variantNames;
    }

    public function addVariantName(VariantName $variantName): self
    {
        if (!$this->variantNames->contains($variantName)) {
            $this->variantNames[] = $variantName;
            $variantName->setProduct($this);
        }

        return $this;
    }

    public function removeVariantName(VariantName $variantName): self
    {
        if ($this->variantNames->removeElement($variantName)) {
            // set the owning side to null (unless already changed)
            if ($variantName->getProduct() === $this) {
                $variantName->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VariantProduct[]
     */
    public function getVariantProducts(): Collection
    {
        return $this->variantProducts;
    }

    public function addVariantProduct(VariantProduct $variantProduct): self
    {
        if (!$this->variantProducts->contains($variantProduct)) {
            $this->variantProducts[] = $variantProduct;
            $variantProduct->setProduct($this);
        }

        return $this;
    }

    public function removeVariantProduct(VariantProduct $variantProduct): self
    {
        if ($this->variantProducts->removeElement($variantProduct)) {
            // set the owning side to null (unless already changed)
            if ($variantProduct->getProduct() === $this) {
                $variantProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function setVariantProducts($vps): self {
        $this->variantProducts = $vps;
        return $this;
    }

    /**
     * @return Collection|VariantRemoved[]
     */
    public function getVariantsRemoved(): Collection
    {
        return $this->variantsRemoved;
    }

    public function addVariantsRemoved(VariantRemoved $variantsRemoved): self
    {
        if (!$this->variantsRemoved->contains($variantsRemoved)) {
            $this->variantsRemoved[] = $variantsRemoved;
            $variantsRemoved->setProduct($this);
        }

        return $this;
    }

    public function removeVariantsRemoved(VariantRemoved $variantsRemoved): self
    {
        if ($this->variantsRemoved->removeElement($variantsRemoved)) {
            // set the owning side to null (unless already changed)
            if ($variantsRemoved->getProduct() === $this) {
                $variantsRemoved->setProduct(null);
            }
        }

        return $this;
    }


}
