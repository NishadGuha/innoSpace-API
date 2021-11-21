<?php

namespace App\Repository;

use App\Entity\Usage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class UsageRepository
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $objectManager;

    /**
     * @param EntityManagerInterface $objectManager
     */
    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Find all usages.
     *
     * @param string $orderBy
     *
     * @return Usage[]
     */
    public function all(string $orderBy = 'id'): array
    {
        return $this
            ->qb()
            ->orderBy(sprintf('usage.%s', $orderBy))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find usage by id.
     *
     * @param  int  $id
     *
     * @return Usage
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): Usage
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('usage.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save usage to database.
     *
     * @param Usage $usage
     */
    public function save(Usage $usage): void
    {
        $this->objectManager->persist($usage);
        $this->objectManager->flush();
    }

    /**
     * Remove usage from database.
     *
     * @param Usage $usage
     */
    public function remove(Usage $usage): void
    {
        $this->objectManager->remove($usage);
        $this->objectManager->flush();
    }

    /**
     * @return int
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function count(): int
    {
        return (int) $this->qb()
            ->select('count(usage.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * @return QueryBuilder
     */
    private function qb(): QueryBuilder
    {
        return $this->objectManager
            ->createQueryBuilder()
            ->select('usage')
            ->from(Usage::class, 'usage');
    }

}
