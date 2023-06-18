<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractRepository implements PasswordUpgraderInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        ManagerRegistry $managerRegistry
    ) {
        parent::__construct($managerRegistry, User::class);
    }

    public function createOrUpdate(mixed $entity, bool $flush = true): void
    {
        $plainPassword = $entity->getPlainPassword();
        if ($plainPassword !== null) {
            $password = $this->userPasswordHasher->hashPassword($entity, $plainPassword);
            $entity->setPassword($password);
            $entity->eraseCredentials();
        }
        parent::createOrUpdate($entity, $flush);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);

        $this->createOrUpdate($user);
    }
}
