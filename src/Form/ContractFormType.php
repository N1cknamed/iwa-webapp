<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_holder', TextType::class)
            ->add('date_start', DateType::class, ['data' => new \DateTime() ])
            ->add('date_end', DateType::class)
            ->add('country_code', TextType::class, ['required' => false])
            ->add('region', TextType::class, ['required' => false])
            ->add('coordinatesType', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    'Above' => 'ABOVE',
                    'Below' => 'BELOW',
                    'Between' => 'BETWEEN',
                    'Radius' => 'RADIUS'
                ]
            ])
            ->add('longitude', NumberType::class, ['required' => false])
            ->add('latitude', NumberType::class, ['required' => false])
            ->add('elevation', NumberType::class, ['required' => false])
            ->add('TEMP', CheckboxType::class, ['required' => false])
            ->add('DEWP', CheckboxType::class, ['required' => false])
            ->add('STP', CheckboxType::class, ['required' => false])
            ->add('SLP', CheckboxType::class, ['required' => false])
            ->add('VISIB', CheckboxType::class, ['required' => false])
            ->add('WDSP', CheckboxType::class, ['required' => false])
            ->add('PRCP', CheckboxType::class, ['required' => false])
            ->add('SNDP', CheckboxType::class, ['required' => false])
            ->add('FRSHTT', CheckboxType::class, ['required' => false])
            ->add('CLDC', CheckboxType::class, ['required' => false])
            ->add('WNDDIR', CheckboxType::class, ['required' => false]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void

    {
        $resolver->setDefaults([
            'data_class' => Contract::class
        ]);
    }
}
