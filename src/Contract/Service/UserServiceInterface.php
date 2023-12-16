<?php

namespace App\Contract\Service;

use App\Dto\UserDto;
use App\Entity\User;

interface UserServiceInterface
{
    /**
     * @param UserDto $dto
     * @param bool $flush
     * @return User
     */
    public function createUser(UserDto $dto, bool $flush = true): User;

    /**
     * @param User $user
     * @param UserDto $dto
     * @param bool $flush
     * @return User
     */
    public function updateUser(User $user, UserDto $dto, bool $flush = true): User;
}
