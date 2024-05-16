<?php

namespace App\Entity;

use App\Repository\PaymentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentTypeRepository::class)]
class PaymentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'paymentType', targetEntity: Orders::class)]
    private Collection $number_order;

    public function __construct()
    {
        $this->number_order = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getNumberOrder(): Collection
    {
        return $this->number_order;
    }

    public function addNumberOrder(Orders $numberOrder): static
    {
        if (!$this->number_order->contains($numberOrder)) {
            $this->number_order->add($numberOrder);
            $numberOrder->setPaymentType($this);
        }

        return $this;
    }

    public function removeNumberOrder(Orders $numberOrder): static
    {
        if ($this->number_order->removeElement($numberOrder)) {
            // set the owning side to null (unless already changed)
            if ($numberOrder->getPaymentType() === $this) {
                $numberOrder->setPaymentType(null);
            }
        }

        return $this;
    }

}
