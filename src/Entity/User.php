<?php

namespace App\Entity;

use App\Entity\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: "mia_user")]
class User extends BaseUser {
    #[ORM\Column(type: "string")]
    protected $roles = [];

    #[ORM\Column(name: "password", type: "string", length: 512, nullable: true)]
    protected $password;

    #[ORM\ManyToOne(targetEntity: Roles::class, inversedBy: "users")]
    private $role;

    public function __construct()
    {
        parent::__construct();
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
}
