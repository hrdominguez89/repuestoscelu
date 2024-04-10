<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\User;
use App\Form\UserType;
use App\Helpers\EnqueueEmail;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Helpers\FileUploader;
use App\Helpers\SendMail;
use App\Repository\CitiesRepository;
use App\Repository\RolesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route("/crud-user")]
class CRUDUserController extends AbstractController
{


    #[Route("/", name: "secure_crud_user_index", methods: ["GET"])]
    public function index(UserRepository $userRepository): Response
    {
        $data['users'] =  $userRepository->findBy(['role' => 2]);
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());

        $data['title'] = 'Sucursales';

        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/crud_user/abm_user.html.twig', $data);
    }

    #[Route("/new", name: "secure_crud_user_new", methods: ["GET", "POST"])]
    public function new(
        Request $request,
        EnqueueEmail $queue,
        RolesRepository $rolesRepository,
        CitiesRepository $citiesRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $data['user'] = new User();
        $data['form'] = $this->createForm(UserType::class, $data['user']);
        $data['form']->handleRequest($request);

        $city = (int)@$request->get('user')['city'];
        if ($data['form']->isSubmitted()) {
            try {

                if ($data['form']->isValid()) {
                    $password = $this->generatePassword();
                    $data['user']->setPassword($password);
                    $data['user']->setChangePassword(true);
                    $data['user']->setChangePasswordDate(null);
                    $data['user']->setRole($rolesRepository->find(2));
                    $data['user']->setCity($citiesRepository->find($city));

                    $entityManager->persist($data['user']);
                    $entityManager->flush();

                    //queue the email
                    $id_email = $queue->enqueue(
                        Constants::EMAIL_TYPE_WELCOME_BACKOFFICE, //tipo de email
                        $data['user']->getEmail(), //email destinatario
                        [ //parametros
                            'name' => $data['user']->getName(),
                            'url_back_login' => $_ENV['SITE_URL'],
                            'email' => $data['user']->getEmail(),
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
                    return $this->redirectToRoute('secure_crud_user_index');
                }
            } catch (Exception $e) {
                $message['type'] = 'modal';
                $message['alert'] = 'danger';
                $message['title'] = 'Error';
                $message['message'] = 'Error al crear la sucursal: .' . $e->getMessage();
                $this->addFlash('message', $message);
                return $this->redirectToRoute('secure_crud_user_index');
            }
        }

        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'user/user.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['user'] = $data['user'];
        $data['title'] = 'Nueva sucursal';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_user_index', 'title' => 'Sucursales'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->renderForm('secure/crud_user/form_user.html.twig', $data);
    }

    #[Route("/{id}/edit", name: "secure_crud_user_edit", methods: ["GET", "POST"])]
    public function edit(
        $id,
        Request $request,
        EnqueueEmail $queue,
        CitiesRepository $citiesRepository,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $data['user'] = $userRepository->findOneBy(['id' => $id, 'active' => true]);

        $data['form'] = $this->createForm(UserType::class, $data['user']);
        $data['form']->handleRequest($request);

        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'user/user.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');

        $city = (int)@$request->get('user')['city'];

        if ($data['form']->isSubmitted()) {
            try {
                if (!$city) {
                    $data['form']->addError(new FormError('La ciudad seleccionada no es válida.'));
                }
                if ($data['form']->isValid()) {
                    if (@$request->get('user')['reset_password']) {
                        $password = $this->generatePassword();
                        $data['user']->setPassword($password);
                        $data['user']->setChangePassword(true);
                        $data['user']->setChangePasswordDate(null);
                        $message['message'] = 'Sucursal editada correctamente. Se envio un e-mail a la cuenta de correo con las nuevas credenciales';
                    } else {
                        $message['message'] = 'Sucursal editada correctamente.';
                    }
                    $data['user']->setCity($citiesRepository->find($request->get('user')['city']));
                    $entityManager->persist($data['user']);
                    $entityManager->flush();
                    if (@$request->get('user')['reset_password']) {
                        //queue the email
                        $id_email = $queue->enqueue(
                            Constants::EMAIL_TYPE_WELCOME_BACKOFFICE, //tipo de email
                            $data['user']->getEmail(), //email destinatario
                            [ //parametros
                                'name' => $data['user']->getName(),
                                'url_back_login' => $_ENV['SITE_URL'],
                                'email' => $data['user']->getEmail(),
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
                    return $this->redirectToRoute('secure_crud_user_index');
                }
            } catch (Exception $e) {
                $message['type'] = 'modal';
                $message['alert'] = 'danger';
                $message['title'] = 'Error';
                $message['message'] = 'Error al editar la sucursal: .' . $e->getMessage();
                $this->addFlash('message', $message);
                return $this->redirectToRoute('secure_crud_user_index');
            }
        }

        $data['title'] = 'Editar sucursal';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_user_index', 'title' => 'Sucursales'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->renderForm('secure/crud_user/form_user.html.twig', $data);
    }

    // #[Route("/{id}", name: "secure_crud_user_delete", methods: ["POST"])]
    // public function delete(EntityManagerInterface $entityManager, Request $request, $id, UserRepository $userRepository): Response
    // {
    //     $user = $userRepository->find($id);


    //     if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
    //         $entityManager = $entityManager;
    //         $entityManager->remove($user);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('secure_crud_user_index');
    // }


    #[Route("/getCities/{state_id}", name: "secure_get_cities_user", methods: ["GET"])]
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
