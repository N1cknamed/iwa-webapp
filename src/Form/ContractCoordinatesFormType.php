<?php

namespace App\Form;

use App\Entity\ContractCoordinates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractCoordinatesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coordinatesType', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    'Above' => 'ABOVE',
                    'Below' => 'BELOW',
                    'Between' => 'BETWEEN',
                    'Radius' => 'RADIUS'
                ]
            ])
            ->add('longitude', NumberType::class)
            ->add('latitude', NumberType::class)
            ->add('elevation', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContractCoordinates::class
        ]);
    }
}
