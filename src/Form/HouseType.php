<?php

namespace App\Form;

use App\Entity\House;
use App\Entity\Neighborhood;
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
    private IdToNeighborhoodTransformer $transformer;

    /**
     * @param IdToNeighborhoodTransformer $transformer
     */
    public function __construct(IdToNeighborhoodTransformer $transformer)
    {
        $this->transformer = $transformer;
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
        ;

        $builder->get('neighborhood')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
