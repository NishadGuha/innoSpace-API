<?php

namespace App\Tests;

use App\Entity\House;
use App\Entity\Usage;
use App\Repository\UsageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsageTest extends KernelTestCase
{
    private $entityManager;

    private $usageRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->usageRepository = $this->createMock(UsageRepository::class);
    }

    public function testAUsageIsCreatedInDatabase(): void
    {
        $house = new House();
        $house->setHouseNumber("1");
        $house->setPhoneNumber("123");

        $this->entityManager->persist($house);
        $this->entityManager->flush();

        $usage = new Usage();
        $usage->setConsumption("10kWh");
        $usage->setDuration("15min");
        $usage->setHouse($house);

        $this->entityManager->persist($usage);
        $this->entityManager->flush();

        $this->usageRepository->expects($this->any())
            ->method('findById')
            ->willReturn($usage);

        /** @var Usage $usageObj */
        $usageObj = $this->usageRepository->findById($usage->getId());

        $this->assertEquals("10kWh", $usageObj->getConsumption());
        $this->assertEquals("15min", $usageObj->getDuration());
        $this->assertNotEmpty($usageObj->getHouse());
        $this->assertNotEmpty($usageObj->getTimeCreated());
        $this->assertEmpty($usageObj->getDevice());
    }
}