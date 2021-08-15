<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 */
class Shop implements Indentifiable
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shops")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="shop", orphanRemoval=true)
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity=VariantLabel::class, mappedBy="shop")
     */
    private $variantLabels;

    /**
     * @ORM\OneToMany(targetEntity=VariantName::class, mappedBy="shop")
     */
    private $variantNames;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->variantLabels = new ArrayCollection();
        $this->variantNames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getproduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setShop($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getShop() === $this) {
                $product->setShop(null);
            }
        }

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
            $variantLabel->setShop($this);
        }

        return $this;
    }

    public function removeVariantLabel(VariantLabel $variantLabel): self
    {
        if ($this->variantLabels->removeElement($variantLabel)) {
            // set the owning side to null (unless already changed)
            if ($variantLabel->getShop() === $this) {
                $variantLabel->setShop(null);
            }
        }

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
            $variantName->setShop($this);
        }

        return $this;
    }

    public function removeVariantName(VariantName $variantName): self
    {
        if ($this->variantNames->removeElement($variantName)) {
            // set the owning side to null (unless already changed)
            if ($variantName->getShop() === $this) {
                $variantName->setShop(null);
            }
        }

        return $this;
    }
}
