<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CitiesRepository;
use App\Repository\CustomerRepository;
use App\Repository\CustomerStatusTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AwsSnsClient;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route("/customer")]
class CrudCustomerController extends AbstractController
{

    #[Route("/", name: "secure_crud_customer_index")]
    public function index(CustomerRepository $customerRepository): Response
    {
        // dd($awsSnsClient->sendSMS('MENSAJE DE PRUEBA','+5491163549766'));
        $data['title'] = 'Clientes';
        $data['customers'] = $customerRepository->listCustomersInfo();
        $data['title'] = "Clientes";
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_customer/abm_customer.html.twig', $data);
    }

    #[Route("/new", name: "secure_crud_customer_new", methods: ["GET", "POST"])]
    public function new(EntityManagerInterface $em, Request $request, CustomerStatusTypeRepository $customerStatusTypeRepository): Response
    {

        $data['title'] = "Nuevo cliente";
        $data['customer'] = new Customer();
        $form = $this->createForm(CustomerType::class, $data['customer']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $status_customer = $customerStatusTypeRepository->find(Constants::CUSTOMER_STATUS_VALIDATED);
            $data['customer']->setStatus($status_customer);
            $data['customer']->setPassword($this->generatePassword());
            $entityManager = $em;
            $entityManager->persist($data['customer']);
            $entityManager->flush();

            //envio por helper los datos del cliente al crm
            return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'customers/customers.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_customer_index', 'title' => 'Clientes'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    }

    #[Route("/{id}/edit", name: "secure_crud_customer_edit", methods: ["GET", "POST"])]
    public function edit(EntityManagerInterface $em, $id, Request $request, CustomerRepository $customerRepository): Response
    {
        $data['title'] = "Editar cliente";
        $data['customer'] = $customerRepository->find($id);
        $form = $this->createForm(CustomerType::class, $data['customer']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            //envio por helper los datos del cliente al crm
            return $this->redirectToRoute('secure_crud_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        $data['form'] = $form;
        $data['files_js'] = array(
            'customers/customers.js?v=' . rand(),
        );
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_customer_index', 'title' => 'Clientes'),
            array('active' => true, 'title' => $data['title'])
        );
        return $this->renderForm('secure/crud_customer/customer_form.html.twig', $data);
    }

    #[Route("/getCities/{state_id}", name: "secure_get_cities", methods: ["GET"])]
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
