<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;

readonly class UserService implements UserServiceInterface
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createUser(UserDto $dto, bool $flush = true): User
    {
        $user = new User();
        self::hydrateUserFromDto($user, $dto);
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }

    /**
     * @param User $user
     * @param UserDto $dto
     * @return void
     */
    public static function hydrateUserFromDto(User $user, UserDto $dto): void
    {
        $user->setEmail($dto->getEmail())
            ->setPlainPassword($dto->getPlainPassword())
            ->addRole($dto->getRole())
            ->setEnabled($dto->isEnabled());
    }

    /**
     * @inheritDoc
     */
    public function updateUser(
        User $user,
        UserDto $dto,
        bool $flush = true
    ): User {
        self::hydrateUserFromDto($user, $dto);
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }
}
