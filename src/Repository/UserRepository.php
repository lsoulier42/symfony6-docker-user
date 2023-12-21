<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
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
    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        ManagerRegistry $managerRegistry
    ) {
        parent::__construct($managerRegistry, User::class);
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     * @return void
     */
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

    /**
     * @param PasswordAuthenticatedUserInterface $user
     * @param string $newHashedPassword
     * @return void
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);

        $this->createOrUpdate($user);
    }

    /**
     * @param string $token
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByToken(
        string $token
    ): ?User {
        $alias = self::USER_ALIAS;
        $queryBuilder = $this->createQueryBuilder($alias);
        self::addFieldAndWhere(
            $queryBuilder,
            $alias,
            'token',
            $token
        );
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByEmail(
        string $email
    ): ?User {
        $email = strtolower(trim($email));
        $alias = self::USER_ALIAS;
        $queryBuilder = $this->createQueryBuilder($alias);
        self::addFieldAndWhere(
            $queryBuilder,
            $alias,
            'email',
            $email
        );
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
