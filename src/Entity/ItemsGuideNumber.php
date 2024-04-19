<?php

namespace App\Entity;

use App\Repository\ItemsGuideNumberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemsGuideNumberRepository::class)]
class ItemsGuideNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "integer")]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: GuideNumbers::class, inversedBy: "itemsGuideNumbers")]
    #[ORM\JoinColumn(nullable: false)]
    private $guide_number;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGuideNumber(): ?GuideNumbers
    {
        return $this->guide_number;
    }

    public function setGuideNumber(?GuideNumbers $guide_number): self
    {
        $this->guide_number = $guide_number;

        return $this;
    }
}
