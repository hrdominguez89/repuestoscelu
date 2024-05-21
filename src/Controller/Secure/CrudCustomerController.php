<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Helpers\EnqueueEmail;
use App\Repository\CitiesRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use App\Repository\RegistrationTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route("/customer")]
class CrudCustomerController extends AbstractController
{

    #[Route("/", name: "secure_crud_customer_index")]
    public function index(CustomerRepository $customerRepository): Response
    {
        $data['title'] = 'Clientes';
        $data['customers'] = $customerRepository->findAll();
        $data['title'] = "Clientes";
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_customer/abm_customer.html.twig', $data);
    }

    #[Route("/new", name: "secure_crud_customer_new", methods: ["GET", "POST"])]
    public function new(EntityManagerInterface $entityManager, Request $request, EnqueueEmail $queue, RegistrationTypeRepository $registrationTypeRepository, CustomerStatusTypeRepository $customerStatusTypeRepository, CitiesRepository $citiesRepository): Response
    {

        $data['title'] = "Nuevo cliente";
        $data['customer'] = new Customer();
        $data['form'] = $this->createForm(CustomerType::class, $data['customer']);
        $data['form']->handleRequest($request);

        $city = (int)@$request->get('customer')['city'];
        if ($data['form']->isSubmitted()) {
            try {
                if (!$city) {
                    $data['form']->addError(new FormError('La ciudad seleccionada no es válida.'));
                }

                if ($data['form']->isValid()) {

                    $data['customer']->setStatus($customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_VALIDATED));
                    $data['customer']->setRegistrationType($registrationTypeRepository->find(Constants::REGISTRATION_TYPE_BACKEND));
                    $password = $this->generatePassword();
                    $data['customer']->setPassword($password);
                    $data['customer']->setChangePassword(true);
                    $data['customer']->setChangePasswordDate(null);
                    $data['customer']->setPoliciesAgree(true);
                    $data['customer']->setCity($citiesRepository->find($city));

                    $entityManager->persist($data['customer']);
                    $entityManager->flush();

                    //queue the email
                    $id_email = $queue->enqueue(
                        Constants::EMAIL_TYPE_WELCOME, //tipo de email
                        $data['customer']->getEmail(), //email destinatario
                        [ //parametros
                            'name' => $data['customer']->getName(),
                            'url_front_login' => $_ENV['FRONT_URL'] . $_ENV['FRONT_LOGIN'],
                            'email' => $data['customer']->getEmail(),
                            'password' => $password
                        ]
                    );

                    //Intento enviar el correo encolado
                    $queue->sendEnqueue($id_email);

                    $message['type'] = 'modal';
                    $message['alert'] = 'success';
                    $message['title'] = 'Éxito';
                    $message['message'] = 'Usuario creado correctamente, Se envió un email a su cuenta de correo con las credenciales.';
                    $this->addFlash('message', $message);

                    return $this->redirectToRoute('secure_crud_customer_index');
                }
            } catch (Exception $e) {
                $message['type'] = 'modal';
                $message['alert'] = 'danger';
                $message['title'] = 'Error';
                $message['message'] = 'Error al crear el usuario: .' . $e->getMessage();
                $this->addFlash('message', $message);
                return $this->redirectToRoute('secure_crud_customer_index');
            }
        }

        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'customers/customers.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_customer_index', 'title' => 'Clientes'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    }

    #[Route("/{id}/edit", name: "secure_crud_customer_edit", methods: ["GET", "POST"])]
    public function edit(EntityManagerInterface $entityManager, $id, Request $request, EnqueueEmail $queue, CustomerRepository $customerRepository, CitiesRepository $citiesRepository): Response
    {
        $data['title'] = "Editar cliente";
        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'customers/customers.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['customer'] = $customerRepository->find($id);
        $data['form'] = $this->createForm(CustomerType::class, $data['customer']);
        $data['form']->handleRequest($request);

        $city = (int)@$request->get('customer')['city'];
        if ($data['form']->isSubmitted()) {
            try {
                if (!$city) {
                    $data['form']->addError(new FormError('La ciudad seleccionada no es válida.'));
                }
                if ($data['form']->isValid()) {
                    if (@$request->get('customer')['reset_password']) {
                        $password = $this->generatePassword();
                        $data['customer']->setPassword($password);
                        $data['customer']->setChangePassword(true);
                        $data['customer']->setChangePasswordDate(null);
                        $message['message'] = 'Usuario editado correctamente. Se envio un e-mail a la cuenta de correo con las nuevas credenciales';
                    } else {
                        $message['message'] = 'Usuario editado correctamente.';
                    }
                    $data['customer']->setCity($citiesRepository->find($city));
                    $entityManager->persist($data['customer']);
                    $entityManager->flush();
                    if (@$request->get('customer')['reset_password']) {
                        //queue the email
                        $id_email = $queue->enqueue(
                            Constants::EMAIL_TYPE_PASSWORD_CHANGE_REQUEST, //tipo de email
                            $data['customer']->getEmail(), //email destinatario
                            [ //parametros
                                'name' => $data['customer']->getName(),
                                'url_front_login' => $_ENV['FRONT_URL'] . $_ENV['FRONT_LOGIN'],
                                'email' => $data['customer']->getEmail(),
                                'password' => $password
                            ]
                        );
    
                        //Intento enviar el correo encolado
                        $queue->sendEnqueue($id_email);
                    }
    
                    $message['type'] = 'modal';
                    $message['alert'] = 'success';
                    $message['title'] = 'Éxito';
                    $this->addFlash('message', $message);
    
                    return $this->redirectToRoute('secure_crud_customer_index');
                }
            } catch (Exception $e) {
                $message['type'] = 'modal';
                $message['alert'] = 'danger';
                $message['title'] = 'Error';
                $message['message'] = 'Error al editar el usuario: .' . $e->getMessage();
                $this->addFlash('message', $message);
                return $this->redirectToRoute('secure_crud_customer_index');
            }
        }


        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_customer_index', 'title' => 'Clientes'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    }

    #[Route("/getCities/{state_id}", name: "secure_get_cities_customer", methods: ["GET"])]
    public function getCities($state_id, CitiesRepository $citiesRepository): Response
    {
        if ((int)$state_id) {
            $data['data'] = $citiesRepository->findCitiesByStateId($state_id);
            if ($data['data']) {
                $data['status'] = true;
            } else {
                $data['status'] = false;
                $data['message'] = 'No se encontraron ciudades con el id indicado';
            }
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontraron ciudades con el id indicado';
        }
        return new JsonResponse($data);
    }

    private function generatePassword()
    {
        // Caracteres disponibles
        $minusculas = 'abcdefghijklmnopqrstuvwxyz';
        $mayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';

        // Asegurar al menos 1 mayúscula, 1 minúscula y 3 números
        $password = $mayusculas[rand(0, strlen($mayusculas) - 1)];
        $password .= $minusculas[rand(0, strlen($minusculas) - 1)];
        for ($i = 0; $i < 3; $i++) {
            $password .= $numeros[rand(0, strlen($numeros) - 1)];
        }

        // Completar hasta 8 caracteres con una mezcla aleatoria de todos los caracteres
        $todosLosCaracteres = $minusculas . $mayusculas . $numeros;
        while (strlen($password) < 8) {
            $password .= $todosLosCaracteres[rand(0, strlen($todosLosCaracteres) - 1)];
        }

        // Mezclar la contraseña para no tener un patrón predecible
        return str_shuffle($password);
    }
}
