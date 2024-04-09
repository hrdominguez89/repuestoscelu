<?php

namespace App\Entity;

use App\Repository\GenderTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenderTypeRepository::class)]
class GenderType
{
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:"integer")]
    private $id;

     #[ORM\Column(type:"string", length:1)]
    private $initials;

     #[ORM\Column(type:"string", length:30)]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitials(): ?string
    {
        return $this->initials;
    }

    public function setInitials(string $initials): self
    {
        $this->initials = $initials;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
}
