<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: "App\Repository\CurrencyRepository")]
#[ORM\Table("mia_currency")]
class Currency
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;


    #[ORM\Column(name: "api_id", type: "string", length: 255, nullable: true)]
    private $apiId;


    #[ORM\Column(name: "name", type: "string", length: 255, unique: true)]
    private $name;


    #[ORM\Column(name: "abbreviation", type: "string", length: 10, nullable: true)]
    private $abbreviation;


    #[ORM\Column(name: "main", type: "boolean")]
    private $main;


    public function getId()
    {
        return $this->id;
    }


    public function getApiId(): ?string
    {
        return $this->apiId;
    }


    public function setApiId(?string $apiId): Currency
    {
        $this->apiId = $apiId;

        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): Currency
    {
        $this->name = $name;

        return $this;
    }


    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }


    public function setAbbreviation(?string $abbreviation): Currency
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }


    public function isMain(): bool
    {
        return $this->main;
    }


    public function setMain(bool $main): Currency
    {
        $this->main = $main;

        return $this;
    }

    public function getMain(): ?bool
    {
        return $this->main;
    }
}
