<?php

namespace App\Dto\User;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class AdminEditUserDto
{
    /**
     * @var string $email
     */
    #[NotBlank]
    #[Email]
    private string $email;

    /**
     * @var bool $enabled
     */
    #[NotNull]
    private bool $enabled;

    /**
     * @var UserRoleEnum $role
     */
    #[NotNull]
    private UserRoleEnum $role;

    /**
     * @param User $user
     */
    public function __construct(
        User $user
    ) {
        $this->email = $user->getEmail();
        $this->enabled = $user->isEnabled();
        $this->role = $user->getMainRoleEnum();
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
     * @return AdminEditUserDto
     */
    public function setEmail(string $email): AdminEditUserDto
    {
        $this->email = $email;
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
     * @return AdminEditUserDto
     */
    public function setEnabled(bool $enabled): AdminEditUserDto
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return UserRoleEnum
     */
    public function getRole(): UserRoleEnum
    {
        return $this->role;
    }

    /**
     * @param UserRoleEnum $role
     * @return AdminEditUserDto
     */
    public function setRole(UserRoleEnum $role): AdminEditUserDto
    {
        $this->role = $role;
        return $this;
    }
}
