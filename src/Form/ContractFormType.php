<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('coordinates', ContractCoordinatesFormType::class, ['required' => false])
            ->add('data', ContractDataFormType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void

    {
        $resolver->setDefaults([
            'data_class' => Contract::class
        ]);
    }
}
