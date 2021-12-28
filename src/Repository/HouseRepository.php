<?php

namespace App\Repository;

use App\Entity\House;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class HouseRepository
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
     * Find all houses.
     *
     * @param string $orderBy
     *
     * @return House[]
     */
    public function all(string $orderBy = 'id'): array
    {
        return $this
            ->qb()
            ->orderBy(sprintf('house.%s', $orderBy))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find house by id.
     *
     * @param  int  $id
     *
     * @return House
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): House
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('house.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find house by house number.
     *
     * @param string $houseNumber
     * @return House
     *
     * @throws NonUniqueResultException
     */
    public function findByHouseNumber(string $houseNumber): House
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('house.houseNumber', ':houseNumber'))
            ->setParameter('houseNumber', $houseNumber)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save house to database.
     *
     * @param House $house
     */
    public function save(House $house): void
    {
        $this->objectManager->persist($house);
        $this->objectManager->flush();
    }

    /**
     * Remove house from database.
     *
     * @param House $house
     */
    public function remove(House $house): void
    {
        $this->objectManager->remove($house);
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
            ->select('count(house.id)')
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
            ->select('house')
            ->from(House::class, 'house');
    }

}
