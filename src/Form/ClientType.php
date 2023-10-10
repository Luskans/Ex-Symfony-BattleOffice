<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => 'Nom'
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'required' => false,
                'first_options' => [
                    'label' => 'Email'
                ],
                'second_options' => [
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
