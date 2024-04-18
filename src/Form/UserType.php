<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\States;
use App\Entity\User;
use App\Repository\StatesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $builder->getData();
        $builder
            ->add('email', EmailType::class, ['label' => 'Correo *','required' => true])
            ->add('name', TextType::class, ['label' => 'Nombre *','attr' => ['placeholder'=>'Nombre del punto de venta','required' => true]])
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
            ->add('street_address',TextType::class,['label'=>'Dirección *','required' => true])
            ->add('number_address',TextType::class,['label'=>'Altura N° *','required' => true])
            ->add('city', ChoiceType::class, [
                'placeholder' => 'Seleccione una localidad/ciudad',
                'label' => 'Localicad/Ciudad',
                'disabled' => true,
                'mapped' => false,
                'required' => true,
            ]);
            if ($user && $user->getId()) {
                $builder->add('reset_password', CheckboxType::class, [
                    'label'    => 'Reiniciar contraseña',
                    'required' => false,
                    'mapped' => false
                ]);
            }
            // ->add('password', PasswordType::class, ['label' => 'Contraseña',])
            // ->add('image', FileType::class, [
            //     'label' => 'Imagen ',

            //     // unmapped means that this field is not associated to any entity property
            //     'mapped' => false,

            //     // make it optional so you don't have to re-upload the PDF file
            //     // every time you edit the Product details
            //     'required' => false,

            //     // unmapped fields can't define their validation using annotations
            //     // in the associated entity, so you can use the PHP constraint classes
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '2048k',
            //             'mimeTypes' => [
            //                 'image/jpeg',
            //                 'image/png',
            //                 'image/svg',
            //             ],
            //             'mimeTypesMessage' => 'Please upload a valid document',
            //         ])
            //     ],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
