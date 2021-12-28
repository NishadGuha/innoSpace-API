<?php

namespace App\Tests;

use App\Entity\DeviceType;
use App\Repository\DeviceTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeviceTypeTest extends KernelTestCase
{
    private $entityManager;

    private $deviceTypeRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->deviceTypeRepository = $this->createMock(DeviceTypeRepository::class);
    }

    public function testANeighborhoodIsCreatedInDatabase(): void
    {
        $deviceType = new DeviceType();
        $deviceType->setType("Heater");

        $this->entityManager->persist($deviceType);
        $this->entityManager->flush();

        $this->deviceTypeRepository->expects($this->any())
            ->method('findById')
            ->willReturn($deviceType);

        /** @var DeviceType $deviceTypeObj */
        $deviceTypeObj = $this->deviceTypeRepository->findById($deviceType->getId());

        $this->assertEquals("Heater", $deviceTypeObj->getType());
        $this->assertEmpty($deviceTypeObj->getDevices());
    }
}