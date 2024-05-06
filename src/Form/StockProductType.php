<?php

namespace App\Form;

use App\Entity\StockProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class StockProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('stock', NumberType::class, [
                'label' => 'Stock *',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('cost', MoneyType::class, [
                'currency' => 'USD',
                'label' => 'Costo *',
                'required' => true,
                'attr' => ['placeholder' => '0.00', 'pattern' => '^\d+(\.\d{1,2}|,\d{1,2})?$', 'title' => 'El formato debe ser 0,00 o 0.00'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+(\.\d{1,2}|,\d{1,2})?$/",
                        'message' => 'El valor debe cumplir con el formato 00,00 o 00.00',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockProduct::class,
        ]);
    }
}
