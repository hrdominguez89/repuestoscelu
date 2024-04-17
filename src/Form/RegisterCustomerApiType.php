<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\Customer;
use App\Form\DataTransformer\StateToEntityTransformer;
use App\Form\DataTransformer\CityToEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use ReCaptcha\ReCaptcha;

class RegisterCustomerApiType extends AbstractType

{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    // Agregar un validador Callback para manejar la validación del correo electrónico basado en el estado del cliente
                    new Callback([$this, 'validateEmailBasedOnStatus'])
                ]
            ])
            ->add('recaptcha_token', TextType::class, [
                'mapped' => false, // No mapear este campo a una propiedad de la entidad
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Callback([$this, 'validateRecaptchaToken'])
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('code_area', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'El código de área debe ser numérico'
                    ])
                ]
            ])
            ->add('cel_phone', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'El campo celular debe ser numérico'
                    ])
                ]
            ])
            ->add('state', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank()
                ]
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank()
                ]
            ])
            ->add('street_address', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank()
                ]
            ])
            ->add('number_address', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'El tipo de dato de provincia no es valido'
                    ])
                ]
            ])
            ->add('floor_apartment', TextType::class, [
                'required' => false,
            ])
            ->add('identity_number', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new NotBlank()
                ]
            ])->add('policies_agree', CheckboxType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(['message' => 'Debe aceptar las políticas']),
                    new NotBlank(['message' => 'Debe aceptar las políticas']),
                    new Type([
                        'type' => 'boolean',
                        'message' => 'El tipo de dato de políticas debe ser booleano'
                    ])
                ]
            ]);
        $builder->get('state')
            ->addModelTransformer(new StateToEntityTransformer($this->entityManager));
        $builder->get('city')
            ->addModelTransformer(new CityToEntityTransformer($this->entityManager));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    // Método para validar el correo electrónico basado en el estado del cliente
    public function validateEmailBasedOnStatus($value, ExecutionContextInterface $context): void
    {
        $existingCustomer = $this->entityManager->getRepository(Customer::class)->findOneBy(['email' => $value]);

        // Si existe un cliente y el estado no es "Pending", lanzar un error de validación
        if ($existingCustomer) {
            switch ($existingCustomer->getStatus()->getId()) {
                case Constants::CUSTOMER_STATUS_PENDING:
                    $context->buildViolation('Esta cuenta se encuentra pendiente de validacion <a href="https://front-test.repuestoscelu.com.ar">Validar</a>')->addViolation();
                    break;
                case Constants::CUSTOMER_STATUS_VALIDATED:
                    $context->buildViolation('Esta cuenta ya se encuentra registrada y validada')->addViolation();
                    break;
                case Constants::CUSTOMER_STATUS_DISABLED:
                    $context->buildViolation('Esta cuenta se encuentra deshabilitada, pongase en contacto con atención al cliente.')->addViolation();
                    break;
            }
        }
    }

    public function validateRecaptchaToken($value, ExecutionContextInterface $context): void
    {
        $recaptcha = new ReCaptcha($_ENV['GOOGLE_RECAPTCHA_SECRET']);
        $recaptchaResponse = $recaptcha->verify($value);

        if (!$recaptchaResponse->isSuccess()) {
            $context->buildViolation('Token reCAPTCHA no válido')->addViolation();
        }
    }
}
