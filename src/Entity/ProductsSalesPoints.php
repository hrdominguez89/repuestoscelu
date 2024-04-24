<?php

namespace App\Entity;

use App\Repository\ProductsSalesPointsRepository;
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
}
