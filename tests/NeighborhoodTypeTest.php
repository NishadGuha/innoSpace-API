<?php

namespace App\Tests;

use App\Entity\Neighborhood;
use App\Form\NeighborhoodType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class NeighborhoodTypeTest extends TypeTestCase
{
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
            'direction' => null,
            'street' => null
        ];

        $model = new Neighborhood();

        $form = $this->factory->create(NeighborhoodType::class, $model);

        $expected = new Neighborhood();

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}