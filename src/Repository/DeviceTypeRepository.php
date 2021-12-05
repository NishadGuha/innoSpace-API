<?php

namespace App\Repository;

use App\Entity\DeviceType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class DeviceTypeRepository
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
     * @return DeviceType[]
     */
    public function all(string $orderBy = 'id'): array
    {
        return $this
            ->qb()
            ->orderBy(sprintf('deviceType.%s', $orderBy))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find house by id.
     *
     * @param  int  $id
     *
     * @return DeviceType
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): DeviceType
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('deviceType.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save house to database.
     *
     * @param DeviceType $deviceType
     */
    public function save(DeviceType $deviceType): void
    {
        $this->objectManager->persist($deviceType);
        $this->objectManager->flush();
    }

    /**
     * Remove house from database.
     *
     * @param DeviceType $deviceType
     */
    public function remove(DeviceType $deviceType): void
    {
        $this->objectManager->remove($deviceType);
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
            ->select('count(deviceType.id)')
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
            ->select('deviceType')
            ->from(DeviceType::class, 'deviceType');
    }
}
