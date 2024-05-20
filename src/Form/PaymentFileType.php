<?php

namespace App\Form;

use App\Entity\PaymentsFiles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;

class PaymentFileType extends AbstractType
{
    private $order;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $order = $options['order'];
        $today = new \DateTime();
        $created = $order->getCreatedAt();

        $builder
            ->add('payment_file', FileType::class, [
                'label' => 'Comprobante de pago *',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube un documento válido (JPEG, PNG, PDF).',
                    ])
                ],
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'USD',
                'label' => 'Monto *',
                'required' => true,
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
            ])
            ->add('date_paid', DateType::class, [
                'label' => 'Fecha de pago *',
                'widget' => 'single_text',
                'required' => true,
                'attr' => [
                    'min' => $created->format('Y-m-d'),
                    'max' => $today->format('Y-m-d'),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentsFiles::class,
            'order' => null
        ]);
    }
}
