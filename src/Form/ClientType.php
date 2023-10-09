<?php

namespace App\Form;

use App\Entity\Client;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', StringType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', StringType::class, [
                'label' => 'Nom'
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'first_option' => [
                    'label' => 'Email'
                ],
                'second_option' => [
                    'label' => 'Confirmation email'
                ]  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
