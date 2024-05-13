<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DispatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $products = $options['products'];

        $builder
            ->add('sale_point', EntityType::class, [
                'placeholder' => 'Seleccione un punto de venta',
                'label' => 'Punto de venta',
                'class'  => User::class,
                'required' => true,
                'choice_label' => function ($user) {
                    return $user->getName() . ' | ' . $user->getState()->getName() . ' - ' . $user->getCity()->getName();
                },
                'query_builder' => function (UserRepository $sp) {
                    return $sp->createQueryBuilder('sp')
                        ->where('sp.visible = :sp_visible')
                        ->andWhere('sp.active =:sp_active')
                        ->andWhere('sp.role =:sp_role')
                        ->setParameter('sp_active', true)
                        ->setParameter('sp_visible', true)
                        ->setParameter('sp_role', Constants::ROLE_SUCURSAL)
                        ->orderBy('sp.name');
                },
                'constraints' => [
                    new Callback([$this, 'validateSalePoint'])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ]);
        foreach ($products as $product) {
            $available = $product->getLastInventory() ? $product->getLastInventory()->getAvailable() : 0;
            $builder
                ->add('quantity_' . $product->getId(), IntegerType::class, [
                    'label' => 'Stock',
                    'required' => false,
                    'attr' => [
                        'min' => $available ? 1 : 0,
                        'max' => $available,
                    ],
                    'constraints' => [
                        new GreaterThan([
                            'value' => 0,
                            'message' => 'El valor de cantidad debe ser mayor que 0.'
                        ]),
                        new LessThanOrEqual([
                            'value' => $available,
                            'message' => 'El valor de cantidad debe ser menor que ' . $available
                        ])
                    ],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'products' => []
        ]);
    }

    public function validateSalePoint($value, ExecutionContextInterface $context): void
    {
        if(!$value){
            $context->buildViolation('Debe seleccionar un punto de venta.')->addViolation();
        }
    }
}
