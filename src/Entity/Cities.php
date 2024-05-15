<?php

namespace App\Entity;

use App\Repository\CitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:CitiesRepository::class)]
class Cities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $name;

    #[ORM\ManyToOne(targetEntity:States::class, inversedBy:"cities")]
    private $state;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $state_code;

    #[ORM\ManyToOne(targetEntity:Countries::class, inversedBy:"cities")]
    private $country;

    #[ORM\Column(type:"string", length:2,nullable:true)]
    private $country_code;

    #[ORM\Column(type:"decimal", precision:10, scale:8, nullable:true)]
    private $latitude;

    #[ORM\Column(type:"decimal", precision:11, scale:8, nullable:true)]
    private $longitude;

    #[ORM\Column(type:"datetime", nullable:false)]
    private $created_at;

    #[ORM\Column(type:"datetime", nullable:true)]
    private $updated_at;

    #[ORM\Column(type:"smallint", nullable:true)]
    private $flag;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $wikiDataId;

    #[ORM\Column(type:"boolean", nullable:true)]
    private $visible;

    #[ORM\OneToMany(targetEntity:Orders::class, mappedBy:"bill_city")]
    private $orders;

    #[ORM\OneToMany(targetEntity:Orders::class, mappedBy:"receiver_city")]
    private $receiver_orders;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Customer::class)]
    private Collection $customers;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->receiver_orders = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->users = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getState(): ?States
    {
        return $this->state;
    }

    public function setState(?States $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStateCode(): ?string
    {
        return $this->state_code;
    }

    public function setStateCode(string $state_code): self
    {
        $this->state_code = $state_code;

        return $this;
    }

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFlag(): ?int
    {
        return $this->flag;
    }

    public function setFlag(int $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getWikiDataId(): ?string
    {
        return $this->wikiDataId;
    }

    public function setWikiDataId(?string $wikiDataId): self
    {
        $this->wikiDataId = $wikiDataId;

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

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setBillCity($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getBillCity() === $this) {
                $order->setBillCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getReceiverOrders(): Collection
    {
        return $this->receiver_orders;
    }

    public function addReceiverOrder(Orders $receiverOrder): self
    {
        if (!$this->receiver_orders->contains($receiverOrder)) {
            $this->receiver_orders[] = $receiverOrder;
            $receiverOrder->setReceiverCity($this);
        }

        return $this;
    }

    public function removeReceiverOrder(Orders $receiverOrder): self
    {
        if ($this->receiver_orders->removeElement($receiverOrder)) {
            // set the owning side to null (unless already changed)
            if ($receiverOrder->getReceiverCity() === $this) {
                $receiverOrder->setReceiverCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCity($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCity() === $this) {
                $user->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setCity($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getCity() === $this) {
                $customer->setCity(null);
            }
        }

        return $this;
    }
}
