<?php

namespace App\Entity;

use App\Repository\ProductSalePointInventoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductSalePointInventoryRepository::class)]
class ProductSalePointInventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productSalePointInventories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?productsSalesPoints $product_sale_point = null;

    #[ORM\Column]
    private ?int $on_hand = null;

    #[ORM\Column]
    private ?int $available = null;

    #[ORM\Column]
    private ?int $committed = null;

    #[ORM\Column]
    private ?int $sold = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductSalePoint(): ?productsSalesPoints
    {
        return $this->product_sale_point;
    }

    public function setProductSalePoint(?productsSalesPoints $product_sale_point): static
    {
        $this->product_sale_point = $product_sale_point;

        return $this;
    }

    public function getOnHand(): ?int
    {
        return $this->on_hand;
    }

    public function setOnHand(int $on_hand): static
    {
        $this->on_hand = $on_hand;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(int $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getCommitted(): ?int
    {
        return $this->committed;
    }

    public function setCommitted(int $committed): static
    {
        $this->committed = $committed;

        return $this;
    }

    public function getSold(): ?int
    {
        return $this->sold;
    }

    public function setSold(int $sold): static
    {
        $this->sold = $sold;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
