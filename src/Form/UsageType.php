<?php

namespace App\Form;

use App\Entity\Usage;
use App\Form\DataTransformer\IdToDeviceTransformer;
use App\Form\DataTransformer\IdToHouseTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class UsageType extends AbstractType
{
    private IdToHouseTransformer $houseTransformer;

    private IdToDeviceTransformer $deviceTransformer;

    /**
     * @param IdToHouseTransformer $houseTransformer
     * @param IdToDeviceTransformer $deviceTransformer
     */
    public function __construct(IdToHouseTransformer $houseTransformer, IdToDeviceTransformer $deviceTransformer)
    {
        $this->houseTransformer = $houseTransformer;
        $this->deviceTransformer = $deviceTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consumption', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('duration', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('house', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('device', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
        ;

        $builder->get('house')->addModelTransformer($this->houseTransformer);
        $builder->get('device')->addModelTransformer($this->deviceTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usage::class,
        ]);
    }
}
