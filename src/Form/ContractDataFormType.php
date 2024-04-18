<?php

namespace App\Form;

use App\Entity\ContractData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('TEMP', RadioType::class)
            ->add('DEWP', RadioType::class)
            ->add('STP', RadioType::class)
            ->add('SLP', RadioType::class)
            ->add('VISIB', RadioType::class)
            ->add('WDSP', RadioType::class)
            ->add('PRCP', RadioType::class)
            ->add('SNDP', RadioType::class)
            ->add('FRSHTT', RadioType::class)
            ->add('CLDC', RadioType::class)
            ->add('WNDDIR', RadioType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContractData::class
        ]);
    }
}
