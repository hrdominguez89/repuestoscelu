<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\ShippingType;
use App\Entity\StatusOrderType;
use App\Repository\StatusOrderTypeRepository;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OrderConfirmType extends AbstractType
{
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->user = $options['user'];

        $builder
            ->add('status', EntityType::class, [
                'placeholder' => 'Seleccione una opción',
                'label' => 'Cambiar estado de la orden',
                'class' => StatusOrderType::class,
                'required' => true,
                'choice_label' => 'name',
                'query_builder' => function (StatusOrderTypeRepository $so) {
                    return $so->createQueryBuilder('so')
                        ->where('so.id != :id')
                        ->setParameter('id', Constants::STATUS_ORDER_OPEN)
                        ->orderBy('so.name');
                },
                'constraints' => [
                    new Callback([$this, 'validateStatus'])
                ]
            ])
            ->add('shipping_type', EntityType::class, [
                'placeholder' => 'Seleccione un tipo de envio',
                'label' => 'Tipo de envio',
                'class' => ShippingType::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'shipping_type_class', 'disabled' => 'disabled']
            ])
            ->add('tracking_name', TextType::class, [
                'label' => 'Empresa de envío',
                'attr' => ['class' => 'tracking_type_class', 'disabled' => 'disabled']
            ])
            ->add('tracking_number', TextType::class, [
                'label' => 'Nro de tracking',
                'attr' => ['class' => 'tracking_type_class', 'disabled' => 'disabled']
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Si el status es "confirmada" (2), entonces shipping_type es requerido
        if (isset($data['status']) && $data['status'] == 2) {
            $form->add('shipping_type', EntityType::class, [
                'class' => ShippingType::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(['message' => 'Debe seleccionar un tipo de envío.']),
                ],
                'placeholder' => 'Seleccione un tipo de envio',
                'label' => 'Tipo de envio',
            ]);
        }

        // Si el shipping_type es "envio a domicilio" (1), entonces tracking_name y tracking_number son requeridos
        if (isset($data['shipping_type']) && $data['shipping_type'] == 1) {
            $form->add('tracking_name', TextType::class, [
                'label' => 'Empresa de envío',
                'constraints' => [
                    new NotBlank(['message' => 'Debe ingresar el nombre de la empresa de envío.']),
                ],
                'attr' => ['class' => 'tracking_type_class']
            ]);
            $form->add('tracking_number', TextType::class, [
                'label' => 'Nro de tracking',
                'constraints' => [
                    new NotBlank(['message' => 'Debe ingresar el número de tracking.']),
                ],
                'attr' => ['class' => 'tracking_type_class']
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
        ]);
    }

    public function validateStatus($value, ExecutionContextInterface $context): void
    {
        if (!$value) {
            $context->buildViolation('Debe seleccionar una opción.')->addViolation();
        }
    }
}
