<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('paymentMethod')
            // ->add('status')
            ->add('client', ClientType::class, [

            ])
            ->add('deliveryAddress', AddressType::class, [

            ])
            ->add('billingAddress', AddressType::class, [

            ])
            ->add('products', ProductType::class, [

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
