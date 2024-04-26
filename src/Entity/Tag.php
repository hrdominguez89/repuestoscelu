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

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: ProductSalePointTag::class)]
    private Collection $productSalePointTags;

    public function __construct()
    {
        $this->visible = false;
        $this->created_at = new \DateTime();
        $this->productSalePointTags = new ArrayCollection();
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
     * @return Collection<int, ProductSalePointTag>
     */
    public function getProductSalePointTags(): Collection
    {
        return $this->productSalePointTags;
    }

    public function addProductSalePointTag(ProductSalePointTag $productSalePointTag): static
    {
        if (!$this->productSalePointTags->contains($productSalePointTag)) {
            $this->productSalePointTags->add($productSalePointTag);
            $productSalePointTag->setTag($this);
        }

        return $this;
    }

    public function removeProductSalePointTag(ProductSalePointTag $productSalePointTag): static
    {
        if ($this->productSalePointTags->removeElement($productSalePointTag)) {
            // set the owning side to null (unless already changed)
            if ($productSalePointTag->getTag() === $this) {
                $productSalePointTag->setTag(null);
            }
        }

        return $this;
    }
}
