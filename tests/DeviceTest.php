<?php

namespace App\Tests;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeviceTest extends KernelTestCase
{
    private $entityManager;

    private $deviceRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->deviceRepository = $this->createMock(DeviceRepository::class);
    }

    public function testADeviceIsCreatedInDatabase(): void
    {
        $device = new Device();
        $device->setMake("TestMake");
        $device->setPluggedIn(true);
        $device->setPriorityRating(1);
        $device->setVoltage("5V");
        $device->setWattage("20W");

        $this->entityManager->persist($device);
        $this->entityManager->flush();

        $this->deviceRepository->expects($this->any())
            ->method('findById')
            ->willReturn($device);

        /** @var Device $deviceObj */
        $deviceObj = $this->deviceRepository->findById($device->getId());

        $this->assertEquals("TestMake", $deviceObj->getMake());
        $this->assertEquals("5V", $deviceObj->getVoltage());
        $this->assertEquals("20W", $deviceObj->getWattage());
        $this->assertEquals(1, $deviceObj->getPriorityRating());
        $this->assertEquals(true, $deviceObj->getPluggedIn());
        $this->assertEmpty($deviceObj->getUsages());
        $this->assertEmpty($deviceObj->getHouse());
        $this->assertEmpty($deviceObj->getDeviceType());
    }
}