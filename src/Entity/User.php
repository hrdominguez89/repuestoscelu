<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
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

    public function __construct()
    {
        parent::__construct();
        $this->change_password = false;
        $this->active = true;
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
}
