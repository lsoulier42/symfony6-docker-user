<?php

namespace App\DataFixtures;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\RegisterDto;
use App\Enum\UserRoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param string $adminPassword
     * @param UserServiceInterface $userService
     */
    public function __construct(
        private readonly string $adminPassword,
        private readonly UserServiceInterface $userService
    ) {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers();
        $manager->flush();
    }

    /**
     * @return void
     */
    public function loadUsers(): void
    {
        $usersData = [
            (new RegisterDto())
                ->setEmail('admin@test.com')
                ->setPlainPassword($this->adminPassword)
                ->setRole(UserRoleEnum::ROLE_ADMIN)
                ->setEnabled(true),
            (new RegisterDto())
                ->setEmail('user@test.com')
                ->setPlainPassword('test1234')
                ->setRole(UserRoleEnum::ROLE_USER)
                ->setEnabled(true)
            ];
        foreach ($usersData as $userData) {
            $this->userService->createUser($userData, false);
        }
    }
}
