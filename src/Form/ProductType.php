<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Inventory;
use App\Entity\Product;
use App\Entity\Specification;
use App\Entity\Tag;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\SpecificationRepository;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre *', 'attr' => ['required' => true]])
            ->add('descriptionEs', TextType::class, ['label' => 'Descripción corta español *', 'required' => true])
            ->add('long_description_es', TextareaType::class, ['label' => 'Descripción larga en español', 'required' => false, 'attr' => ['rows' => 4]])
            ->add('category', EntityType::class, [
                'class'  => Category::class,
                'placeholder' => 'Seleccione una categoría',
                'label' => 'Categoría *',
                'choice_label' => function ($category, $key, $index) {
                    return $category->getName() . ' - ' . $category->getNomenclature();
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
            ->add('brand', EntityType::class, [
                'class'  => Brand::class,
                'placeholder' => 'Seleccione una marca',
                'label' => 'Marca *',
                'choice_label' => function ($brand, $key, $index) {
                    return $brand->getName() . ' - ' . $brand->getNomenclature();
                },
                'required' => true,
            ])
            ->add('model', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $br) {
                    return $br->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_MODEL)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione un modelo.',
                'label' => 'Modelo *',
                'required' => true,
                'choice_label' => 'name',
            ])
            ->add('color', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $br) {
                    return $br->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_COLOR)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione Color.',
                'label' => 'Color',
                'choice_label' => 'name',
            ])
            ->add('cod', TextType::class, ['label' => 'Cod.', 'required' => false, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('screen_resolution', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_SCREEN_RESOLUTION)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo de resolución de pantalla.',
                'label' => 'Resolución de pantalla',
                'required' => false,
                'choice_label' => 'name',
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
        ]);
    }
}
