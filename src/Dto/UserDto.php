<?php

namespace App\Dto;

use App\Enum\UserRoleEnum;
use Symfony\Component\Validator\Constraints\NotNull;

class UserDto extends AbstractUserDto
{
    /**
     * @var bool $enabled
     */
    #[NotNull]
    private bool $enabled = false;

    /**
     * @var UserRoleEnum $role
     */
    #[NotNull]
    private UserRoleEnum $role = UserRoleEnum::ROLE_USER;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return UserDto
     */
    public function setEnabled(bool $enabled): UserDto
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
     * @return UserDto
     */
    public function setRole(UserRoleEnum $role): UserDto
    {
        $this->role = $role;
        return $this;
    }
}
