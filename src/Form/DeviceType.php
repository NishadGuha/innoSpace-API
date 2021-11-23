<?php

namespace App\Form;

use App\Entity\Device;
use App\Form\DataTransformer\IdToHouseTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class DeviceType extends AbstractType
{
    private IdToHouseTransformer $transformer;

    /**
     * @param IdToHouseTransformer $transformer
     */
    public function __construct(IdToHouseTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('wattage', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('voltage', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('house', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
        ;

        $builder->get('house')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
        ]);
    }
}
