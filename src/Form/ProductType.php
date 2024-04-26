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
use Symfony\Component\Validator\Constraints\NotNull;

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
            ->add('cod', TextType::class, ['label' => 'Cod.', 'required' => true, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('description', TextType::class, ['label' => 'Descripción corta *', 'required' => true])
            ->add('long_description', TextareaType::class, ['label' => 'Descripción larga', 'required' => false, 'attr' => ['rows' => 4]])
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
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ],
                'data' => @$product->getSubcategory() ? $product->getSubcategory()->getName() : '',
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
            ->add('model', TextType::class, [
                'attr' => ['list' => 'model_names', 'required' => 'required', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Modelo *',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => @$product->getModel() ? $product->getModel()->getName() : '',
            ])
            ->add('color', TextType::class, [
                'attr' => ['list' => 'color_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Color',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getColor() ? $product->getColor()->getName() : '',
            ])

            ->add('screenResolution', TextType::class, [
                'attr' => ['list' => 'screenResolution_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Resolución de pantalla',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getScreenResolution() ? $product->getScreenResolution()->getName() : '',
            ])
            ->add('CPU', TextType::class, [
                'attr' => ['list' => 'CPU_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'CPU',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getCPU() ? $product->getCPU()->getName() : '',
            ])
            ->add('GPU', TextType::class, [
                'attr' => ['list' => 'GPU_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'GPU',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getGPU() ? $product->getGPU()->getName() : '',
            ])
            ->add('memory', TextType::class, [
                'attr' => ['list' => 'memory_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Memoria',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getMemory() ? $product->getMemory()->getName() : '',
            ])
            ->add('storage', TextType::class, [
                'attr' => ['list' => 'storage_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Almacenamiento',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getStorage() ? $product->getStorage()->getName() : '',
            ])
            ->add('screenSize', TextType::class, [
                'attr' => ['list' => 'screenSize_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Tamaño de pantalla',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getScreenSize() ? $product->getScreenSize()->getName() : '',
            ])
            ->add('OS', TextType::class, [
                'attr' => ['list' => 'OS_names', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'],
                'label' => 'Sistema Operativo',
                'required' => false,
                'mapped' => false,
                'data' => @$product->getOS() ? $product->getOS()->getName() : '',
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
