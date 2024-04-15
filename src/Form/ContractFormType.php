<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('coordinates', TextType::class, ['required' => false])
            ->add('longitude', NumberType::class, ['required' => false])
            ->add('latitude', NumberType::class, ['required' => false])
            ->add('elevation', NumberType::class, ['required' => false])
            ->add('data', CollectionType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class
        ]);
    }
}
