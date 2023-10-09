<?php

namespace App\Form;

use App\Entity\Address;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('line1', StringType::class, [
                'label' => 'Adresse'
            ]) 
            ->add('line2', StringType::class, [
                'label' => 'Complément adr.'
            ])
            ->add('city', StringType::class, [
                'label' => 'Ville'
            ])
            ->add('zipcode', IntegerType::class, [
                'label' => 'Code postal'
            ])
            ->add('country', CountryType::class, [
                
            ])
            ->add('phone', StringType::class, [
                'label' => 'Téléphone'
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
