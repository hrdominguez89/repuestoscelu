<?php

namespace App\Entity;

use App\Repository\OrdersProductsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersProductsRepository::class)]
class OrdersProducts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: "ordersProducts")]
    #[ORM\JoinColumn(nullable: false)]
    private $number_order;

    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $cod;

    #[ORM\Column(type: "float", nullable: true)]
    private $price;

    #[ORM\Column(type: "integer")]
    private $quantity;

    #[ORM\OneToOne(targetEntity: ShoppingCart::class, inversedBy: "ordersProducts", cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: true)]
    private $shopping_cart;

    #[ORM\ManyToOne(inversedBy: 'ordersProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductsSalesPoints $productsSalesPoints = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOrder(): ?Orders
    {
        return $this->number_order;
    }

    public function setNumberOrder(?Orders $number_order): self
    {
        $this->number_order = $number_order;

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

    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(?string $cod): self
    {
        $this->cod = $cod;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shopping_cart;
    }

    public function setShoppingCart(ShoppingCart $shopping_cart): self
    {
        $this->shopping_cart = $shopping_cart;

        return $this;
    }

    public function getProductsSalesPoints(): ?ProductsSalesPoints
    {
        return $this->productsSalesPoints;
    }

    public function setProductsSalesPoints(?ProductsSalesPoints $productsSalesPoints): static
    {
        $this->productsSalesPoints = $productsSalesPoints;

        return $this;
    }
}
