<?php

namespace App\Repository;

use App\Entity\Neighborhood;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class NeighborhoodRepository
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
     * Find all neighborhoods.
     *
     * @param string $orderBy
     *
     * @return Neighborhood[]
     */
    public function all(string $orderBy = 'id'): array
    {
        return $this
            ->qb()
            ->orderBy(sprintf('neighborhood.%s', $orderBy))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find neighborhood by id.
     *
     * @param  int  $id
     *
     * @return Neighborhood
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): Neighborhood
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('neighborhood.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save neighborhood to database.
     *
     * @param Neighborhood $neighborhood
     */
    public function save(Neighborhood $neighborhood): void
    {
        $this->objectManager->persist($neighborhood);
        $this->objectManager->flush();
    }

    /**
     * Remove neighborhood from database.
     *
     * @param Neighborhood $neighborhood
     */
    public function remove(Neighborhood $neighborhood): void
    {
        $this->objectManager->remove($neighborhood);
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
            ->select('count(neighborhood.id)')
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
            ->select('neighborhood')
            ->from(Neighborhood::class, 'neighborhood');
    }

}
