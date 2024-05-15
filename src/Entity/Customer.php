<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



#[ORM\Entity(repositoryClass: "App\Repository\CustomerRepository")]
#[ORM\Table("mia_customer")]
#[UniqueEntity("email")]
class Customer extends BaseUser
{
    const ROLE_DEFAULT = 'ROLE_CUSTOMER';

    #[ORM\Column(name: "cel_phone", type: "string", length: 100, nullable: true)]
    #[Assert\Length(min: 2, max: 100)]
    public $cel_phone;

    #[ORM\ManyToOne(targetEntity: RegistrationType::class, inversedBy: "customers")]
    public $registration_type;

    #[ORM\Column(type: "datetime", nullable: false)]

    public $registration_date;

    #[ORM\Column(type: "string", nullable: true)]
    private $verification_code;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private $change_password;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $change_password_date;

    #[ORM\ManyToOne(targetEntity: CustomerStatusType::class, inversedBy: "customers")]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private $identity_number;

    #[ORM\OneToMany(targetEntity: Orders::class, mappedBy: "customer")]
    private $orders;

    #[ORM\OneToMany(targetEntity: FavoriteProduct::class, mappedBy: "customer")]
    private $favoriteProducts;

    #[ORM\OneToMany(targetEntity: ShoppingCart::class, mappedBy: "customer")]
    private $shoppingCarts;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?States $state = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cities $city = null;

    #[ORM\Column]
    private ?int $code_area = null;

    #[ORM\Column(length: 255)]
    private ?string $street_address = null;

    #[ORM\Column(length: 255)]
    private ?string $number_address = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $floor_apartment = null;

    #[ORM\Column(nullable: false)]
    private bool $policies_agree;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datetime_verification_code = null;


    public function __construct()
    {
        parent::__construct();

        $this->roles = json_encode([self::ROLE_DEFAULT]);
        $this->registration_date = new \DateTime();
        $this->change_password = false;
        $this->orders = new ArrayCollection();
        $this->favoriteProducts = new ArrayCollection();
        $this->shoppingCarts = new ArrayCollection();
    }


    public function setPassword(?string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return array_unique(['ROLE_CUSTOMER']);
    }

    public function getCustomerTotalInfo(): array
    {

        return [
            'id' => (int) $this->getId(),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'identity_number' => $this->getIdentityNumber(),
            'status_id' => $this->getStatus()->getId(),
            'status_name' => $this->getStatus()->getName(),
            'created_at' => $this->getRegistrationDate()->format('Y-m-d H:i:s'),
        ];
    }

    public function getCelPhone(): ?string
    {
        return $this->cel_phone;
    }

    public function setCelPhone(?string $cel_phone): self
    {
        $this->cel_phone = $cel_phone;

        return $this;
    }

    public function getRegistrationType(): ?RegistrationType
    {
        return $this->registration_type;
    }

    public function setRegistrationType(?RegistrationType $registration_type): self
    {
        $this->registration_type = $registration_type;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(?\DateTimeInterface $registration_date): self
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getVerificationCode()
    {
        return $this->verification_code;
    }

    public function setVerificationCode($verification_code): self
    {
        if($verification_code){
            $this->verification_code = password_hash($verification_code, PASSWORD_BCRYPT);
        }else{
            $this->verification_code = null;
        }

        return $this;
    }

    public function getChangePassword(): ?bool
    {
        return $this->change_password;
    }

    public function setChangePassword(bool $change_password): self
    {
        $this->change_password = $change_password;

        return $this;
    }

    public function getChangePasswordDate(): ?\DateTimeInterface
    {
        return $this->change_password_date;
    }

    public function setChangePasswordDate(?\DateTimeInterface $change_password_date): self
    {
        $this->change_password_date = $change_password_date;

        return $this;
    }

    public function getStatus(): ?CustomerStatusType
    {
        return $this->status;
    }

    public function setStatus(?CustomerStatusType $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdentityNumber(): ?string
    {
        return $this->identity_number;
    }

    public function setIdentityNumber(?string $identity_number): self
    {
        $this->identity_number = $identity_number;

        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    public function getFavoriteProducts(): Collection
    {
        return $this->favoriteProducts;
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if (!$this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts[] = $favoriteProduct;
            $favoriteProduct->setCustomer($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->removeElement($favoriteProduct)) {
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getCustomer() === $this) {
                $favoriteProduct->setCustomer(null);
            }
        }

        return $this;
    }

    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->setCustomer($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->removeElement($shoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCart->getCustomer() === $this) {
                $shoppingCart->setCustomer(null);
            }
        }

        return $this;
    }

    public function getState(): ?States
    {
        return $this->state;
    }

    public function setState(?States $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCity(): ?Cities
    {
        return $this->city;
    }

    public function setCity(?Cities $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCodeArea(): ?int
    {
        return $this->code_area;
    }

    public function setCodeArea(int $code_area): static
    {
        $this->code_area = $code_area;

        return $this;
    }

    public function getStreetAddress(): ?string
    {
        return $this->street_address;
    }

    public function setStreetAddress(string $street_address): static
    {
        $this->street_address = $street_address;

        return $this;
    }

    public function getNumberAddress(): ?string
    {
        return $this->number_address;
    }

    public function setNumberAddress(string $number_address): static
    {
        $this->number_address = $number_address;

        return $this;
    }

    public function getFloorApartment(): ?string
    {
        return $this->floor_apartment;
    }

    public function setFloorApartment(?string $floor_apartment): static
    {
        $this->floor_apartment = $floor_apartment;

        return $this;
    }

    public function isPoliciesAgree(): ?bool
    {
        return $this->policies_agree;
    }

    public function setPoliciesAgree(bool $policies_agree): static
    {
        $this->policies_agree = $policies_agree;

        return $this;
    }

    public function getDatetimeVerificationCode(): ?\DateTimeInterface
    {
        return $this->datetime_verification_code;
    }

    public function setDatetimeVerificationCode(?\DateTimeInterface $datetime_verification_code): static
    {
        $this->datetime_verification_code = $datetime_verification_code;

        return $this;
    }
}
