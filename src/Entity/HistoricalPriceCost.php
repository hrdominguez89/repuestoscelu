<?php

namespace App\Entity;

use App\Repository\HistoricalPriceCostRepository;
use Doctrine\ORM\Mapping as ORM;

 #[ORM\Entity(repositoryClass:HistoricalPriceCostRepository::class)]
class HistoricalPriceCost
{
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:"integer")]
    private $id;

     #[ORM\Column(type:"float")]
    private $cost;

     #[ORM\Column(type:"float",nullable:true)]
    private $price;

     #[ORM\Column(type:"datetime",nullable:false)]
    private $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
