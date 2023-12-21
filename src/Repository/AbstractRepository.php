<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public const STRING USER_ALIAS = 'user';

    /**
     * @param ManagerRegistry $registry
     * @param class-string $className
     */
    public function __construct(ManagerRegistry $registry, string $className)
    {
        parent::__construct($registry, $className);
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     * @return void
     */
    public function createOrUpdate(mixed $entity, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager();
        if ($entity->getId() === null) {
            $entityManager->persist($entity);
        }
        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @param mixed $entity
     * @param bool $flush
     * @return void
     */
    public function remove(mixed $entity, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return QueryBuilder
     */
    public static function addFieldLike(
        QueryBuilder $queryBuilder,
        string $alias,
        string $fieldName,
        mixed $fieldValue,
    ): QueryBuilder {
        $orx = new Orx();
        self::formatOrxLike($queryBuilder, $orx, $alias, $fieldName, $fieldValue);
        return $queryBuilder->andWhere($queryBuilder->expr()->orX($orx));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Orx $orx
     * @param string $alias
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return void
     */
    private static function formatOrxLike(
        QueryBuilder $queryBuilder,
        Orx $orx,
        string $alias,
        string $fieldName,
        mixed $fieldValue
    ): void {
        $fieldWithAlias = "$alias.$fieldName";
        $likeVersions = ["%$fieldValue%", "$fieldValue%", "%$fieldValue"];
        foreach ($likeVersions as $version) {
            $orx->add($queryBuilder->expr()->like($fieldWithAlias, $queryBuilder->expr()->literal($version)));
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @param string $fieldName
     * @param mixed $fieldValue
     * @return QueryBuilder
     */
    public static function addFieldAndWhere(
        QueryBuilder $queryBuilder,
        string $alias,
        string $fieldName,
        mixed $fieldValue,
    ): QueryBuilder {
        return $queryBuilder->andWhere("$alias.$fieldName = :$fieldName")
            ->setParameter($fieldName, $fieldValue);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $parentAlias
     * @param string $relationField
     * @param string $childAlias
     * @param string $joinType
     * @return QueryBuilder
     */
    public static function addTableJoin(
        QueryBuilder $queryBuilder,
        string $parentAlias,
        string $relationField,
        string $childAlias,
        string $joinType = Join::LEFT_JOIN
    ): QueryBuilder {
        if (self::hasAlias($queryBuilder, $childAlias)) {
            return $queryBuilder;
        }
        $relation = "$parentAlias.$relationField";
        if ($joinType === Join::INNER_JOIN) {
            return $queryBuilder->innerJoin($relation, $childAlias);
        }
        return $queryBuilder->leftJoin($relation, $childAlias);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @return bool
     */
    private static function hasAlias(
        QueryBuilder $queryBuilder,
        string $alias
    ): bool {
        return in_array($alias, $queryBuilder->getAllAliases(), true);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return Collection
     */
    public static function getCollectionFromQueryBuilder(QueryBuilder $queryBuilder): Collection
    {
        return new ArrayCollection($queryBuilder->getQuery()->getResult());
    }
}
