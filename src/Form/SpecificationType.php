<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\Color;
use App\Entity\CPU;
use App\Entity\GPU;
use App\Entity\Memory;
use App\Entity\Model;
use App\Entity\OS;
use App\Entity\ScreenResolution;
use App\Entity\ScreenSize;
use App\Entity\Storage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SpecificationType extends AbstractType
{

    private $entityManager;

    private $entidad;
    private $old_id;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $name = $options['name'];
        $this->old_id = $options['old_id'];
        $this->entidad = $options['entidad'];

        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nombre',
                    'mapped' => false,
                    'attr' => ['style' => 'text-transform: uppercase'],
                    'data' => $name,
                    'constraints' => [
                        // Agregar un validador Callback para manejar la validación del correo electrónico basado en el estado del cliente
                        new Callback([$this, 'validateIfExistSpecification'])
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'name' => null,
            'old_id' => null,
            'entidad' => null
        ]);
    }

    public function getEntidad()
    {
        switch ($this->entidad) {
            case (Constants::SPECIFICATION_SCREEN_RESOLUTION):
                return ScreenResolution::class;
            case (Constants::SPECIFICATION_SCREEN_SIZE):
                return ScreenSize::class;
            case (Constants::SPECIFICATION_CPU):
                return CPU::class;
            case (Constants::SPECIFICATION_GPU):
                return GPU::class;
            case (Constants::SPECIFICATION_MEMORY):
                return Memory::class;
            case (Constants::SPECIFICATION_STORAGE):
                return Storage::class;
            case (Constants::SPECIFICATION_SO):
                return OS::class;
            case (Constants::SPECIFICATION_COLOR):
                return Color::class;
            case (Constants::SPECIFICATION_MODEL):
                return Model::class;
        }
    }


    public function validateIfExistSpecification($value, ExecutionContextInterface $context): void
    {
        $existSpecification = $this->entityManager->getRepository($this->getEntidad())->findOneBy(['name' => strtoupper($value)]);
        if ($existSpecification) {
            if ($existSpecification->getId() != $this->old_id) {
                $context->buildViolation('La especificación indicada ya se encuentra registrada')->addViolation();
            }
        }
    }
}
