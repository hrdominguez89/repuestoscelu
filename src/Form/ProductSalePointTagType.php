<?php

namespace App\Form;

use App\Entity\ProductSalePointTag;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSalePointTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tag', EntityType::class, [
                'class'  => Tag::class,
                'query_builder' => function (TagRepository $tag) {
                    return $tag->createQueryBuilder('t')
                        ->where('t.visible = :visible')
                        ->setParameter('visible', true)
                        ->orderBy('t.name');
                },
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'placeholder' => 'Seleccione una etiqueta.',
                'label' => 'Etiqueta',
                'required' => false,
                'choice_label' => 'name',
                // Establecer los valores predeterminados
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
