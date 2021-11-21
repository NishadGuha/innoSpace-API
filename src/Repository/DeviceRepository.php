<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class DeviceRepository
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
     * Find all devices.
     *
     * @param string $orderBy
     *
     * @return Device[]
     */
    public function all(string $orderBy = 'id'): array
    {
        return $this
            ->qb()
            ->orderBy(sprintf('device.%s', $orderBy))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find device by id.
     *
     * @param  int  $id
     *
     * @return Device
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): Device
    {
        return $this
            ->qb()
            ->where($this->qb()->expr()->eq('device.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Save device to database.
     *
     * @param Device $device
     */
    public function save(Device $device): void
    {
        $this->objectManager->persist($device);
        $this->objectManager->flush();
    }

    /**
     * Remove device from database.
     *
     * @param Device $device
     */
    public function remove(Device $device): void
    {
        $this->objectManager->remove($device);
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
            ->select('count(device.id)')
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
            ->select('device')
            ->from(Device::class, 'device');
    }

}
