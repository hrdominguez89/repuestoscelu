<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\AboutUsRepository")]
#[ORM\Table(name: "mia_about_us")]
class AboutUs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\Column(name: "description", type: "text", nullable: true)]
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): AboutUs
    {
        $this->description = $description;

        return $this;
    }
}
