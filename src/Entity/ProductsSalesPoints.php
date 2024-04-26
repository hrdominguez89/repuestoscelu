<?php

namespace App\Entity;

use App\Repository\ProductsSalesPointsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsSalesPointsRepository::class)]
class ProductsSalesPoints
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productsSalesPoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?product $product = null;

    #[ORM\ManyToOne(inversedBy: 'productsSalesPoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sale_point = null;

    #[ORM\OneToMany(mappedBy: 'product_sale_point', targetEntity: ProductSalePointTag::class)]
    private Collection $productSalePointTags;

    public function __construct()
    {
        $this->productSalePointTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?product
    {
        return $this->product;
    }

    public function setProduct(?product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getSalePoint(): ?User
    {
        return $this->sale_point;
    }

    public function setSalePoint(?User $sale_point): static
    {
        $this->sale_point = $sale_point;

        return $this;
    }

    /**
     * @return Collection<int, ProductSalePointTag>
     */
    public function getProductSalePointTags(): Collection
    {
        return $this->productSalePointTags;
    }

    public function addProductSalePointTag(ProductSalePointTag $productSalePointTag): static
    {
        if (!$this->productSalePointTags->contains($productSalePointTag)) {
            $this->productSalePointTags->add($productSalePointTag);
            $productSalePointTag->setProductSalePoint($this);
        }

        return $this;
    }

    public function removeProductSalePointTag(ProductSalePointTag $productSalePointTag): static
    {
        if ($this->productSalePointTags->removeElement($productSalePointTag)) {
            // set the owning side to null (unless already changed)
            if ($productSalePointTag->getProductSalePoint() === $this) {
                $productSalePointTag->setProductSalePoint(null);
            }
        }

        return $this;
    }
}
