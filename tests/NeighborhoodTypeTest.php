<?php

namespace App\Tests;

use App\Entity\Neighborhood;
use App\Form\NeighborhoodType;
use Symfony\Component\Form\Test\TypeTestCase;

class NeighborhoodTypeTest extends TypeTestCase
{
    public function testSubmitValidData() {
        $formData = [
            'direction' => 'North',
            'street' => 'Teststraat'
        ];

        $model = new Neighborhood();

        $form = $this->factory->create(NeighborhoodType::class, $model);

        $expected = new Neighborhood();

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }

    public function testCustomFormView() {
        $formData = new Neighborhood();

        $view = $this->factory->create(NeighborhoodType::class, $formData)
            ->createView();

        dump($view->vars);
        die;

        $this->assertArrayHasKey();
    }
}