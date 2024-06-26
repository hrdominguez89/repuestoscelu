<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\FavoriteProductRepository")]
#[ORM\Table(name: "mia_favorite_product")]
class FavoriteProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\ManyToOne(targetEntity: StatusTypeFavorite::class, inversedBy: "favoriteProducts")]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $updated_at;

    #[ORM\ManyToOne(targetEntity: ProductsSalesPoints::class, inversedBy: "favoriteProducts")]
    #[ORM\JoinColumn(nullable: false)]
    private $productsSalesPoints;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "favoriteProducts")]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getStatus(): ?StatusTypeFavorite
    {
        return $this->status;
    }

    public function setStatus(?StatusTypeFavorite $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getProductsSalesPoints(): ?ProductsSalesPoints
    {
        return $this->productsSalesPoints;
    }

    public function setProductsSalesPoints(?ProductsSalesPoints $productsSalesPoints): self
    {
        $this->productsSalesPoints = $productsSalesPoints;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
