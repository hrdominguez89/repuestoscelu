<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: "mia_user")]
#[UniqueEntity("email")]
class User extends BaseUser
{
    #[ORM\Column(type: "string")]
    protected $roles = [];

    #[ORM\Column(name: "password", type: "string", length: 512, nullable: true)]
    protected $password;

    #[ORM\ManyToOne(targetEntity: Roles::class, inversedBy: "users")]
    private $role;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?States $state = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cities $city = null;

    #[ORM\Column(options: ["default" => 1])]
    private ?bool $active = null;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private bool $change_password = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $change_password_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $verification_code = null;

    #[ORM\Column(length: 255, options: ['default' => ''])]
    private ?string $street_address = '';

    #[ORM\Column(length: 20, options: ['default' => ''])]
    private ?string $number_address = '';

    #[ORM\Column(options: ['default' => true])]
    private ?bool $visible = true;

    #[ORM\OneToMany(mappedBy: 'sale_point', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'sale_point', targetEntity: ProductsSalesPoints::class)]
    private Collection $productsSalesPoints;

    public function __construct()
    {
        parent::__construct();
        $this->change_password = false;
        $this->active = true;
        $this->visible = true;
        $this->street_address = '';
        $this->number_address = '';
        $this->products = new ArrayCollection();
        $this->productsSalesPoints = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return array_unique([$this->role->getRole()]);
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isChangePassword(): ?bool
    {
        return $this->change_password;
    }

    public function setChangePassword(bool $change_password): static
    {
        $this->change_password = $change_password;

        return $this;
    }

    public function getChangePasswordDate(): ?\DateTimeInterface
    {
        return $this->change_password_date;
    }

    public function setChangePasswordDate(?\DateTimeInterface $change_password_date): static
    {
        $this->change_password_date = $change_password_date;

        return $this;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verification_code;
    }

    public function setVerificationCode(?string $verification_code): static
    {
        $this->verification_code = $verification_code;

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

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setSalePoint($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSalePoint() === $this) {
                $product->setSalePoint(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductsSalesPoints>
     */
    public function getProductsSalesPoints(): Collection
    {
        return $this->productsSalesPoints;
    }

    public function addProductsSalesPoint(ProductsSalesPoints $productsSalesPoint): static
    {
        if (!$this->productsSalesPoints->contains($productsSalesPoint)) {
            $this->productsSalesPoints->add($productsSalesPoint);
            $productsSalesPoint->setSalePoint($this);
        }

        return $this;
    }

    public function removeProductsSalesPoint(ProductsSalesPoints $productsSalesPoint): static
    {
        if ($this->productsSalesPoints->removeElement($productsSalesPoint)) {
            // set the owning side to null (unless already changed)
            if ($productsSalesPoint->getSalePoint() === $this) {
                $productsSalesPoint->setSalePoint(null);
            }
        }

        return $this;
    }
}
