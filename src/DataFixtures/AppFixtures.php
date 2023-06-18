<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly string $adminPassword,
        private readonly UserRepository $userRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager): void
    {
        $usersData = [[
            'admin',
            'admin@test.com',
            $this->adminPassword,
            UserRoleEnum::ROLE_ADMIN,
            true
        ]];
        foreach ($usersData as $userData) {
            $user = new User();
            $user->setUsername($userData[0])
                ->setEmail($userData[1])
                ->setPlainPassword($userData[2])
                ->addRole($userData[3])
                ->setEnabled($userData[4]);
            $this->userRepository->createOrUpdate($user, false);
        }
    }
}
