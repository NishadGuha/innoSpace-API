<?php

namespace App\Tests;

use App\Entity\House;
use App\Repository\HouseRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HouseTest extends KernelTestCase
{
    private $entityManager;

    private $houseRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->houseRepository = $this->createMock(HouseRepository::class);
    }

    public function testAHouseIsCreatedInDatabase()
    {
        $house = new House();
        $house->setHouseNumber("1");
        $house->setPhoneNumber("123");
        $house->setEmail("abc@xyz.com");
        $house->setOccupants(3);

        $this->entityManager->persist($house);
        $this->entityManager->flush();

        $this->houseRepository->expects($this->any())
            ->method('findByHouseNumber')
            ->willReturn($house);

        $houseObj = $this->houseRepository->findByHouseNumber("1");

        $this->assertEquals("abc@xyz.com", $houseObj->getEmail());
        $this->assertEquals("123", $houseObj->getPhoneNumber());
        $this->assertEquals(3, $houseObj->getOccupants());
        $this->assertEmpty($houseObj->getDevices());
        $this->assertEmpty($houseObj->getHouseType());
        $this->assertEmpty($houseObj->getUsages());
        $this->assertEmpty($houseObj->getNeighborhood());
    }

}