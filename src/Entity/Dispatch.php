<?php

namespace App\Entity;

use App\Repository\DispatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DispatchRepository::class)]
class Dispatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dispatches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $sale_point = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'dispatches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DispatchStatusType $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'dispatch', targetEntity: ProductDispatch::class)]
    private Collection $productDispatches;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->productDispatches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalePoint(): ?user
    {
        return $this->sale_point;
    }

    public function setSalePoint(?user $sale_point): static
    {
        $this->sale_point = $sale_point;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?DispatchStatusType
    {
        return $this->status;
    }

    public function setStatus(?DispatchStatusType $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeInterface $modified_at): static
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    /**
     * @return Collection<int, ProductDispatch>
     */
    public function getProductDispatches(): Collection
    {
        return $this->productDispatches;
    }

    public function addProductDispatch(ProductDispatch $productDispatch): static
    {
        if (!$this->productDispatches->contains($productDispatch)) {
            $this->productDispatches->add($productDispatch);
            $productDispatch->setDispatch($this);
        }

        return $this;
    }

    public function removeProductDispatch(ProductDispatch $productDispatch): static
    {
        if ($this->productDispatches->removeElement($productDispatch)) {
            // set the owning side to null (unless already changed)
            if ($productDispatch->getDispatch() === $this) {
                $productDispatch->setDispatch(null);
            }
        }

        return $this;
    }
}
