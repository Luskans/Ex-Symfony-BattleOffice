<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', ClientType::class, [
                'required' => false,
            ])
            ->add('deliveryAddress', AddressType::class, [
                'required' => false,
            ])
            ->add('billingAddress', AddressType::class, [
                'required' => false,
            ])
            ->add('paymentMethod', PaymentMethodType::class, [
                'required' => false,
                'attr' => [
                    'id' => 'paymentMethodInput',
                ],
            ])
            ->add('product', ProductType::class, [
                'required' => false,
                'attr' => [
                    'id' => 'productInput',
                ],
            ])
            ->add('status', StatusType::class, [
                'required' => false,
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
