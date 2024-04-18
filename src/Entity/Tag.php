<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: "App\Repository\TagRepository")]
#[ORM\Table(name: "mia_tag")]
#[UniqueEntity(fields: "name", message: "La etiqueta indicada ya se encuentra registrada.")]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;


    #[ORM\Column(name: "name", type: "string", length: 255)]
    private $name;


    #[ORM\Column(name: "slug", type: "string", length: 255)]
    private $slug;

    #[ORM\Column(type: "boolean", nullable: true)]
    private $visible;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: "tag")]
    private $products;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false])]
    private $principal;

    public function __construct()
    {
        $this->visible = false;
        $this->created_at = new \DateTime();
        $this->products = new ArrayCollection();
        $this->principal = false;
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
    public function setName(string $name): Tag
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setTag($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getTag() === $this) {
                $product->setTag(null);
            }
        }

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }
}
