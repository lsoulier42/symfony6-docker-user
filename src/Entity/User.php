<?php

namespace App\Entity;

use App\Enum\UserRoleEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(unique: true)]
    #[NotBlank]
    private string $username;

    #[ORM\Column(unique: true)]
    #[NotBlank]
    #[Email]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    private ?string $plainPassword = null;

    #[ORM\Column]
    private bool $enabled = true;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRoleEnum::defaultRole();

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $rolesArray = [];
        foreach ($roles as $role) {
            if (UserRoleEnum::exists($role) && $role !== UserRoleEnum::defaultRole()) {
                $rolesArray[] = $role;
            }
        }
        $this->roles = array_unique($rolesArray);

        return $this;
    }

    public function addRole(UserRoleEnum $roleEnum): User
    {
        $roles = new ArrayCollection($this->roles);
        $newRoleName = $roleEnum->name;
        if (!$roles->contains($newRoleName) && $newRoleName !== UserRoleEnum::defaultRole()) {
            $roles->add($newRoleName);
        }
        $this->roles = $roles->toArray();
        return $this;
    }

    public function removeRole(UserRoleEnum $roleEnum): User
    {
        $roles = new ArrayCollection($this->roles);
        $newRoleName = $roleEnum->name;
        if ($roles->contains($newRoleName) && $newRoleName !== UserRoleEnum::defaultRole()) {
            $roles->removeElement($newRoleName);
        }
        $this->roles = $roles->toArray();
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;
        return $this;
    }
}
