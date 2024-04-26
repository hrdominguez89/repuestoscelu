<?php

namespace App\Entity;

use App\Repository\ProductSalePointTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductSalePointTagRepository::class)]
class ProductSalePointTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productSalePointTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductsSalesPoints $product_sale_point = null;

    #[ORM\ManyToOne(inversedBy: 'productSalePointTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $tag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductSalePoint(): ?ProductsSalesPoints
    {
        return $this->product_sale_point;
    }

    public function setProductSalePoint(?ProductsSalesPoints $product_sale_point): static
    {
        $this->product_sale_point = $product_sale_point;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }
}
