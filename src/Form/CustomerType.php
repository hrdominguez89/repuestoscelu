<?php

namespace App\Form;

use App\Entity\Countries;
use App\Entity\Customer;
use App\Entity\CustomersTypesRoles;
use App\Entity\GenderType;
use App\Entity\States;
use App\Repository\StatesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $customer = $builder->getData();
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre y Apellido / Empresa *', 'required' => true])
            ->add('email', EmailType::class, ['label' => 'Email *', 'required' => true])
            ->add('identity_number', IntegerType::class, ['label' => 'DNI / CUIL / CUIT *', 'required' => true])
            ->add('code_area', IntegerType::class, ['label' => 'Cód. área*', 'required' => true])
            ->add('cel_phone', TextType::class, ['label' => 'Nro de celular *', 'required' => true])
            ->add('state', EntityType::class, [
                'placeholder' => 'Seleccione una provincia',
                'label' => 'Provincia',
                'class'  => States::class,
                'choice_label' => 'name',
                'required' => true,
                'query_builder' => function (StatesRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.visible = :visible')
                        ->orderBy('s.name')
                        ->setParameter('visible', true);
                },
            ])
            ->add('city', ChoiceType::class, [
                'placeholder' => 'Seleccione una localidad/ciudad',
                'label' => 'Localicad/Ciudad',
                'disabled' => true,
                'mapped' => false,
                'required' => true,
            ])
            ->add('street_address', TextType::class, ['label' => 'Dirección *', 'required' => true])
            ->add('number_address', TextType::class, ['label' => 'Altura *', 'required' => true])
            ->add('floor_apartment', TextType::class, ['label' => 'Piso/Depto']);
        if ($customer && $customer->getId()) {
            $builder->add('reset_password', CheckboxType::class, [
                'label'    => 'Reiniciar contraseña',
                'required' => false,
                'mapped' => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
