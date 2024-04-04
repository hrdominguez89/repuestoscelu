<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\AdvertisementsRepository")]
#[ORM\Table("mia_advertisements")]
class Advertisements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\Column(name: "src1", type: "string", length: 255, nullable: true)]
    private $src1;

    #[ORM\Column(name: "src_sm1", type: "string", length: 255, nullable: true)]
    private $srcSm1;

    #[ORM\Column(name: "src2", type: "string", length: 255, nullable: true)]
    private $src2;

    #[ORM\Column(name: "src_sm2", type: "string", length: 255, nullable: true)]
    private $srcSm2;

    #[ORM\Column(name: "src3", type: "string", length: 255, nullable: true)]
    private $src3;

    #[ORM\Column(name: "src_sm3", type: "string", length: 255, nullable: true)]
    private $srcSm3;

    public function getId()
    {
        return $this->id;
    }

    public function getSrc1(): ?string
    {
        return $this->src1;
    }

    public function setSrc1(?string $src1): Advertisements
    {
        $this->src1 = $src1;

        return $this;
    }

    public function getSrc2(): ?string
    {
        return $this->src2;
    }

    public function setSrc2(?string $src2): Advertisements
    {
        $this->src2 = $src2;

        return $this;
    }

    public function getSrc3(): ?string
    {
        return $this->src3;
    }

    public function setSrc3(?string $src3): Advertisements
    {
        $this->src3 = $src3;

        return $this;
    }

    public function getSrcSm1(): ?string
    {
        return $this->srcSm1;
    }

    public function setSrcSm1(?string $srcSm1): Advertisements
    {
        $this->srcSm1 = $srcSm1;

        return $this;
    }

    public function getSrcSm2(): ?string
    {
        return $this->srcSm2;
    }

    public function setSrcSm2(?string $srcSm2): Advertisements
    {
        $this->srcSm2 = $srcSm2;

        return $this;
    }

    public function getSrcSm3(): ?string
    {
        return $this->srcSm3;
    }

    public function setSrcSm3(?string $srcSm3): Advertisements
    {
        $this->srcSm3 = $srcSm3;

        return $this;
    }

    public function getBanners()
    {
        return [
            [
                "id" => 1,
                "format" => "desktop",
                "image" => $this->getSrc1()
            ],
            [
                "id" => 2,
                "format" => "mobile",
                "image" => $this->getSrcSm1()
            ],
            [
                "id" => 3,
                "format" => "desktop",
                "image" => $this->getSrc2()
            ],
            [
                "id" => 4,
                "format" => "mobile",
                "image" => $this->getSrcSm2()
            ],
            [
                "id" => 5,
                "format" => "desktop",
                "image" => $this->getSrc3()
            ],
            [
                "id" => 6,
                "format" => "mobile",
                "image" => $this->getSrcSm3()
            ],
        ];
    }
}
