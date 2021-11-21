<?php

namespace App\Form;

use App\Entity\Neighborhood;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class NeighborhoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('direction', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
            ->add('street', TextType::class, [
                "constraints" => [
                    new NotNull()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Neighborhood::class,
        ]);
    }
}
