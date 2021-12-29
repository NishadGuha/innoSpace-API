<?php

namespace App\Tests;

use App\Entity\Neighborhood;
use App\Repository\NeighborhoodRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class NeighborhoodTest extends KernelTestCase
{
    private $entityManager;

    private $neighborhoodRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->neighborhoodRepository = $this->createMock(NeighborhoodRepository::class);
    }

    public function testANeighborhoodIsCreatedInDatabase(): void
    {
        $neighborhood = new Neighborhood();
        $neighborhood->setStreet("Teststraat");
        $neighborhood->setDirection("North");

        $this->entityManager->persist($neighborhood);
        $this->entityManager->flush();

        $this->neighborhoodRepository->expects($this->any())
            ->method('findByStreet')
            ->willReturn($neighborhood);

        /** @var Neighborhood $neighborhoodObj */
        $neighborhoodObj = $this->neighborhoodRepository->findByStreet("Teststraat");

        $this->assertEquals("North", $neighborhoodObj->getDirection());
        $this->assertEmpty($neighborhoodObj->getHouses());
    }
}
