<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\DispatchStatusType;
use App\Repository\DispatchStatusTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DispatchConfirmType extends AbstractType
{

    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $this->user = $options['user'];

        if ($this->user->getRole()->getId() == Constants::ROLE_SUPER_ADMIN) {
            $builder
                ->add('status', EntityType::class, [
                    'placeholder' => 'Seleccione una opción',
                    'label' => 'Confirmar orden',
                    'class'  => DispatchStatusType::class,
                    'required' => true,
                    'choice_label' => 'name',
                    'query_builder' => function (DispatchStatusTypeRepository $sp) {
                        return $sp->createQueryBuilder('s')
                            ->where('s.id = :id')
                            ->setParameter('id', Constants::STATUS_DISPATCH_CANCELED)
                            ->orderBy('s.name');
                    },
                    'constraints' => [
                        new Callback([$this, 'validateStatus'])
                    ]
                ]);
        } else {
            $builder
                ->add('status', EntityType::class, [
                    'placeholder' => 'Seleccione una opción',
                    'label' => 'Confirmar orden',
                    'class'  => DispatchStatusType::class,
                    'required' => true,
                    'choice_label' => 'name',
                    'query_builder' => function (DispatchStatusTypeRepository $sp) {
                        return $sp->createQueryBuilder('s')
                            ->where('s.id != :id')
                            ->setParameter('id', Constants::STATUS_DISPATCH_IN_TRANSIT)
                            ->orderBy('s.name');
                    },
                    'constraints' => [
                        new Callback([$this, 'validateStatus'])
                    ]
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
