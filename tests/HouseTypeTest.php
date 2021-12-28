<?php

namespace App\Tests;

use App\Entity\HouseType;
use App\Repository\HouseTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HouseTypeTest extends KernelTestCase
{
    private $entityManager;

    private $houseTypeRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->houseTypeRepository = $this->createMock(HouseTypeRepository::class);
    }

    public function testAHouseTypeIsCreatedInDatabase(): void
    {
        $houseType = new HouseType();
        $houseType->setType("Apartment");

        $this->entityManager->persist($houseType);
        $this->entityManager->flush();

        $this->houseTypeRepository->expects($this->any())
            ->method('findById')
            ->willReturn($houseType);

        /** @var HouseType $houseTypeObj */
        $houseTypeObj = $this->houseTypeRepository->findById($houseType->getId());

        $this->assertEquals("Apartment", $houseTypeObj->getType());
        $this->assertEmpty($houseTypeObj->getHouses());
    }
}