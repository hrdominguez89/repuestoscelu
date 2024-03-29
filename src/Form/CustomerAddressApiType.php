<?php

namespace App\Form;

use App\Entity\Cities;
use App\Entity\Countries;
use App\Entity\CustomerAddresses;
use App\Entity\States;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


class CustomerAddressApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('country', EntityType::class, [
                'class'  => Countries::class,
                'constraints' => [
                    new NotNull(),
                    new NotBlank()
                ]
            ])
            ->add('state', EntityType::class, [
                'class'  => States::class,
            ])
            ->add('city', EntityType::class, [
                'class'  => Cities::class,
            ])
            ->add('street', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('number_street', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('floor', TextType::class)
            ->add('department', TextType::class)
            ->add('postal_code', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('additional_info', TextType::class)
            ->add('home_address', CheckboxType::class)
            ->add('billing_address', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerAddresses::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
