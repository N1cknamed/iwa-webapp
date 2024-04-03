<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_holder', TextType::class)
            ->add('date_end', DateType::class)
            ->add('country_code', TextType::class)
            ->add('region', TextType::class)
            ->add('coordinates', TextType::class)
            ->add('longitude', FloatType::class)
            ->add('latitude', FloatType::class)
            ->add('elevation', FloatType::class)
            ->add('data', ArrayType::class)
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class
        ]);
    }
}
