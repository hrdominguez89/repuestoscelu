<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Specification;
use App\Entity\User;
use App\Repository\SpecificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userRole = $options['user_role'];
        $product = $builder->getData();

        if ($userRole === Constants::ROLE_SUPER_ADMIN) {
            $builder->add('sale_point', EntityType::class, [
                'class'  => User::class,
                'query_builder' => function (UserRepository $u) {
                    return $u->createQueryBuilder('u')
                        ->where('u.role = :role')
                        ->setParameter('role', Constants::ROLE_SUCURSAL)
                        ->orderBy('u.name');
                },
                'placeholder' => 'Seleccione un punto de venta.',
                'label' => 'Punto de venta *',
                'required' => true,
                'choice_label' => 'name',
            ]);
        }

        $builder
            ->add('name', TextType::class, ['label' => 'Nombre *', 'attr' => ['required' => true]])
            ->add('cod', TextType::class, ['label' => 'Cod.', 'required' => false, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('description', TextType::class, ['label' => 'Descripción corta español *', 'required' => true])
            ->add('long_description', TextareaType::class, ['label' => 'Descripción larga en español', 'required' => false, 'attr' => ['rows' => 4]])
            ->add('category', EntityType::class, [
                'class'  => Category::class,
                'placeholder' => 'Seleccione una categoría',
                'label' => 'Categoría *',
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'required' => true,
            ])
            ->add('subcategory', ChoiceType::class, [
                'placeholder' => 'Seleccione una subcategoría',
                'label' => 'Subcategoría',
                'disabled' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('brand', TextType::class, [
                'attr' => ['list' => 'brand_names', 'required' => 'required', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Marca *',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => @$product->getBrand() ? $product->getBrand()->getName() : '',
            ])
            ->add('models', TextType::class, [
                'attr' => ['list' => 'models_names', 'required' => 'required', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Modelo *',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => @$product->getModels() ? $product->getModels()->getName() : '',
            ])
            ->add('colors', TextType::class, [
                'attr' => ['list' => 'colors_names', 'required' => 'required', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Color',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => @$product->getColors() ? $product->getColors()->getName() : '',
            ])
            
            ->add('screenResolution', TextType::class, [
                'attr' => ['list' => 'screenResolution_names', 'required' => 'required', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Resolución de pantalla',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => @$product->getScreenResolution() ? $product->getScreenResolution()->getName() : '',
            ])
            ->add('cpu', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $cpu) {
                    return $cpu->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_CPU)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo de CPU.',
                'label' => 'CPU',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('gpu', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $gpu) {
                    return $gpu->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_GPU)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo de GPU.',
                'label' => 'GPU',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('memory', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $mem) {
                    return $mem->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_MEMORY)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo memoria.',
                'label' => 'Memoria',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('storage', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $sto) {
                    return $sto->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_STORAGE)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tamaño de almacenamiento.',
                'label' => 'Tamaño (Capacidad de almacenamiento)',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('screen_size', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $ss) {
                    return $ss->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_SCREEN_SIZE)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tamaño de pantalla.',
                'label' => 'Tamaño de pantalla',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('op_sys', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $os) {
                    return $os->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_SO)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione sistema operativo.',
                'label' => 'O.S.',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('conditium', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $con) {
                    return $con->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_CONDITUM)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione condición del producto.',
                'label' => 'Condición *',
                'required' => true,
                'choice_label' => 'name',
            ])

            ->add('images', HiddenType::class, [
                'mapped' => false,
                'data' => [],
                'attr' => [
                    'data-type' => 'array'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'user_role' => null
        ]);
    }
}
