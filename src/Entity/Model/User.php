<?php

namespace App\Entity\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    protected $id;

    #[ORM\Column(name: "email", type: "string", length: 512)]
    protected $email;

    #[ORM\Column(name: "password", type: "string", length: 512, nullable: true)]
    protected $password;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: true)]
    protected $image;

    #[ORM\Column(name: "name", type: "string", length: 255, nullable: false)]
    #[Assert\Type("string")]
    #[Assert\NotBlank]
    protected $name;

    #[ORM\Column(name: "roles", type: "string")]
    protected $roles;

    public function __construct()
    {
        $this->roles = self::ROLE_DEFAULT;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            // Incluye aquí otras propiedades que necesitas serializar
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        // Restaura aquí otras propiedades deserializadas
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        // La sal no es necesaria si utilizas bcrypt o argon2i
        return null;
    }

    public function eraseCredentials()
    {
        // Implementa este método si necesitas limpiar cualquier dato sensible
    }

    public function getRoles(): array
    {
        // $roles = $this->roles;
        // Asegúrate de incluir al menos un rol para cada usuario
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
