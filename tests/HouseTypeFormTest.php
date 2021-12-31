<?php

namespace App\Tests;

use App\Entity\House;
use App\Form\DataTransformer\IdToHouseTypeTransformer;
use App\Form\DataTransformer\IdToNeighborhoodTransformer;
use App\Form\HouseType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class HouseTypeFormTest extends TypeTestCase
{
    private $neighborhoodTransformer;

    private $houseTypeTransformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->neighborhoodTransformer = $this->createMock(IdToNeighborhoodTransformer::class);
        $this->houseTypeTransformer = $this->createMock(IdToHouseTypeTransformer::class);
    }

    protected function getExtensions(): array
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData() {
        $formData = [
            'email' => null,
            'phone_number' => null,
            'house_number' => null,
            'neighborhood' => null,
            'occupants' => null,
            'houseType' => null
        ];

        $model = new House();

        $form = $this->factory->create(HouseType::class, $model);

        $expected = new House();

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}