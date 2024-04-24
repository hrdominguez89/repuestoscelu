<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: "App\Repository\BrandRepository")]
#[ORM\Table(name: "mia_brand")]
#[UniqueEntity(fields: "name", message: "El nombre indicado ya se encuentra registrado.")]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\Column(name: "name", type: "string", length: 255, unique: true)]
    private $name;

    #[ORM\Column(name: "slug", type: "string", length: 255, nullable: false)]
    private $slug;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: "brand")]
    private $products;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false])]
    private $visible = false;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->visible = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Brand
    {
        $this->name = strtoupper($name);

        $slugify = new Slugify();

        $this->slug = $slugify->slugify($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "slug" => $this->getSlug(),
        ];
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

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
