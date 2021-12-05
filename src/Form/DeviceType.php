<?php

namespace App\Form;

use App\Entity\Device;
use App\Form\DataTransformer\IdToDeviceTypeTransformer;
use App\Form\DataTransformer\IdToHouseTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class DeviceType extends AbstractType
{
    private IdToHouseTransformer $houseTransformer;

    private IdToDeviceTypeTransformer $deviceTypeTransformer;

    /**
     * @param IdToHouseTransformer $houseTransformer
     * @param IdToDeviceTypeTransformer $deviceTypeTransformer
     */
    public function __construct(IdToHouseTransformer $houseTransformer, IdToDeviceTypeTransformer $deviceTypeTransformer)
    {
        $this->houseTransformer = $houseTransformer;
        $this->deviceTypeTransformer = $deviceTypeTransformer;
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
            ->add('priority_rating', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('house', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('deviceType', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
        ;

        $builder->get('house')->addModelTransformer($this->houseTransformer);

        $builder->get('deviceType')->addModelTransformer($this->deviceTypeTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
        ]);
    }
}
