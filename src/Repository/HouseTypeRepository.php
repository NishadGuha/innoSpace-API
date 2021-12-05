<?php

namespace App\Repository;

use App\Entity\HouseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class HouseTypeRepository
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
     * @return HouseType[]
     */
    public function all(string $orderBy = 'id'): array
    {
        return $this
            ->qb()
            ->orderBy(sprintf('houseType.%s', $orderBy))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find house by id.
     *
     * @param  int  $id
     *
     * @return HouseType
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): HouseType
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('houseType.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save house to database.
     *
     * @param HouseType $houseType
     */
    public function save(HouseType $houseType): void
    {
        $this->objectManager->persist($houseType);
        $this->objectManager->flush();
    }

    /**
     * Remove house from database.
     *
     * @param HouseType $houseType
     */
    public function remove(HouseType $houseType): void
    {
        $this->objectManager->remove($houseType);
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
            ->select('count(houseType.id)')
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
            ->select('houseType')
            ->from(HouseType::class, 'houseType');
    }
}
