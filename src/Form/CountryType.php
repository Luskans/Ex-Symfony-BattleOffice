<?php

namespace App\Form;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countries = $this->entityManager->getRepository(Country::class)->findAll();
        $choices = [];

        foreach ($countries as $country) {
            // $choices[] = $country->getName();
            $choices[$country->getName()] = $country->getName();
        }

        $builder
            // ->add('name', ChoiceType::class, [
            //     'choices' => $choices,
            //     'required' => true,
            //     'label' => 'Pays'
            // ])
            ->add('name', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                    'Luxembourg' => 'Luxembourg'
                ],
                'required' => true,
                'label' => 'Pays'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
