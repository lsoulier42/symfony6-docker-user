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
    /**
     * @var string $email
     */
    #[ORM\Column(unique: true)]
    #[NotBlank]
    #[Email]
    private string $email;

    /**
     * @var array $roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null $password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var string|null $plainPassword
     */
    private ?string $plainPassword = null;

    /**
     * @var bool $enabled
     */
    #[ORM\Column]
    private bool $enabled = true;

    /**
     * @var string|null $token
     */
    #[ORM\Column(nullable: true)]
    private ?string $token = null;

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    /**
     * @return array|string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRoleEnum::defaultRole();

        return array_unique($roles);
    }

    /**
     * @return UserRoleEnum
     */
    public function getMainRoleEnum(): UserRoleEnum
    {
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            if ($role !== UserRoleEnum::defaultRole()) {
                return UserRoleEnum::fromString($role);
            }
        }
        return UserRoleEnum::defaultRoleEnum();
    }

    /**
     * @return string
     */
    public function getMainRole(): string
    {
        return $this->getMainRoleEnum()->name;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array(UserRoleEnum::ROLE_ADMIN->name, $this->getRoles(), true);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
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

    /**
     * @param UserRoleEnum $roleEnum
     * @return $this
     */
    public function addRole(UserRoleEnum $roleEnum): self
    {
        $roles = new ArrayCollection($this->roles);
        $newRoleName = $roleEnum->name;
        if (!$roles->contains($newRoleName) && $newRoleName !== UserRoleEnum::defaultRole()) {
            $roles->add($newRoleName);
        }
        $this->roles = $roles->toArray();
        return $this;
    }

    /**
     * @param UserRoleEnum $roleEnum
     * @return $this
     */
    public function removeRole(UserRoleEnum $roleEnum): self
    {
        $roles = new ArrayCollection($this->roles);
        $newRoleName = $roleEnum->name;
        if ($roles->contains($newRoleName) && $newRoleName !== UserRoleEnum::defaultRole()) {
            $roles->removeElement($newRoleName);
        }
        $this->roles = $roles->toArray();
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return void
     */
    public function eraseCredentials(): void
    {
        $this->setPlainPassword(null);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return User
     */
    public function setToken(?string $token): User
    {
        $this->token = $token;
        return $this;
    }
}
