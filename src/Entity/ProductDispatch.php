<?php

namespace App\Entity;

use App\Repository\ProductDispatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductDispatchRepository::class)]
class ProductDispatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productDispatches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dispatch $dispatch = null;

    #[ORM\ManyToOne(inversedBy: 'productDispatches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDispatch(): ?Dispatch
    {
        return $this->dispatch;
    }

    public function setDispatch(?Dispatch $dispatch): static
    {
        $this->dispatch = $dispatch;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
