<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\GreaterThan;

class StocksProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $products = $options['products'];
        $builder->add('description', TextareaType::class, [
            'label' => 'Obvservaciones/Información adicional (se aplicará a cada producto)',
            'required' => false,
        ]);

        foreach ($products as $product) {
            $builder
                ->add('stock_' . $product->getId(), IntegerType::class, [
                    'label' => 'Stock',
                    'required' => false,
                    'attr' => [
                        'min' => 1
                    ],
                    'constraints' => [
                        new GreaterThan([
                            'value' => 0,
                            'message' => 'El valor del stock debe ser mayor que 0.'
                        ]),
                    ],
                ])
                ->add('cost_' . $product->getId(), MoneyType::class, [
                    'currency' => 'USD',
                    'label' => 'Costo *',
                    'required' => false,
                    'attr' => [
                        'placeholder' => '0.00',
                        'pattern' => '^\d+(\.\d{1,2}|,\d{1,2})?$',
                        'title' => 'El formato debe ser 0,00 o 0.00',
                    ],
                    'constraints' => [
                        new Regex([
                            'pattern' => "/^\d+(\.\d{1,2}|,\d{1,2})?$/",
                            'message' => 'El valor debe cumplir con el formato 00,00 o 00.00',
                        ]),
                    ]
                ]);

            // Agregar evento para validar requerimientos de stock o costo
            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($product) {
                $form = $event->getForm();

                $stock = $form->get('stock_' . $product->getId())->getViewData();
                $cost = $form->get('cost_' . $product->getId())->getViewData();

                if (($stock !== null && $stock !== '') && ($cost === null || $cost === '')) {
                    $form->get('cost_' . $product->getId())->addError(new FormError('El campo Costo es requerido si el campo Stock está completado.'));
                }

                if (($cost !== null && $cost !== '') && ($stock === null || $stock === '')) {
                    $form->get('stock_' . $product->getId())->addError(new FormError('El campo Stock es requerido si el campo Costo está completado.'));
                }

                // Validación para el campo cost_
                $costField = $form->get('cost_' . $product->getId());
                $costValue = $costField->getData();

                // Aplicar las restricciones
                if ($costValue !== null && !preg_match("/^\d+(\.\d{1,2}|,\d{1,2})?$/", $costValue)) {
                    $costField->addError(new FormError('El valor debe cumplir con el formato 00,00 o 00.00'));
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'products' => [],
        ]);
    }
}
