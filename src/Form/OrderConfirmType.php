<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\StatusOrderType;
use App\Repository\StatusOrderTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
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
                'class'  => StatusOrderType::class,
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
            ]);
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
