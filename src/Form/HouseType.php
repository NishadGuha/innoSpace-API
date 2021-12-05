<?php

namespace App\Form;

use App\Entity\House;
use App\Entity\Neighborhood;
use App\Form\DataTransformer\IdToHouseTypeTransformer;
use App\Form\DataTransformer\IdToNeighborhoodTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class HouseType extends AbstractType
{
    private IdToNeighborhoodTransformer $neighborhoodTransformer;

    private IdToHouseTypeTransformer $houseTypeTransformer;

    /**
     * @param IdToNeighborhoodTransformer $neighborhoodTransformer
     * @param IdToHouseTypeTransformer $houseTypeTransformer
     */
    public function __construct(IdToNeighborhoodTransformer $neighborhoodTransformer, IdToHouseTypeTransformer $houseTypeTransformer)
    {
        $this->neighborhoodTransformer = $neighborhoodTransformer;
        $this->houseTypeTransformer = $houseTypeTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('phone_number', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('house_number', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('neighborhood', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('occupants', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('houseType', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
        ;

        $builder->get('neighborhood')->addModelTransformer($this->neighborhoodTransformer);

        $builder->get('houseType')->addModelTransformer($this->houseTypeTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
