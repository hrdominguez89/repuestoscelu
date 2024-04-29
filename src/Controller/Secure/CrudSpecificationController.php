<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Color;
use App\Entity\CPU;
use App\Entity\GPU;
use App\Entity\Memory;
use App\Entity\Model;
use App\Entity\OS;
use App\Entity\ScreenResolution;
use App\Entity\ScreenSize;
use App\Entity\Specification;
use App\Entity\SpecificationTypes;
use App\Entity\Storage;
use App\Form\SpecificationType;
use App\Repository\ColorRepository;
use App\Repository\CPURepository;
use App\Repository\GPURepository;
use App\Repository\MemoryRepository;
use App\Repository\ModelRepository;
use App\Repository\OSRepository;
use App\Repository\ScreenResolutionRepository;
use App\Repository\ScreenSizeRepository;
use App\Repository\SpecificationRepository;
use App\Repository\SpecificationTypesRepository;
use App\Repository\StorageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/specifications")]
class CrudSpecificationController extends AbstractController
{

    #[Route("/", name: "secure_crud_specification_type_index", methods: ["GET"])]
    public function index(): Response
    {
        $data['specification_types'] = [
            [
                "name" => "Resolución de pantalla",
                "id" => Constants::SPECIFICATION_SCREEN_RESOLUTION
            ],
            [
                "name" => "Tamaño de pantalla",
                "id" => Constants::SPECIFICATION_SCREEN_SIZE
            ],
            [
                "name" => "CPU",
                "id" => Constants::SPECIFICATION_CPU
            ],
            [
                "name" => "GPU",
                "id" => Constants::SPECIFICATION_GPU
            ],
            [
                "name" => "Memoria",
                "id" => Constants::SPECIFICATION_MEMORY
            ],
            [
                "name" => "Almacenamiento",
                "id" => Constants::SPECIFICATION_STORAGE
            ],
            [
                "name" => "Sistema Operativo",
                "id" => Constants::SPECIFICATION_SO
            ],
            [
                "name" => "Color",
                "id" => Constants::SPECIFICATION_COLOR
            ],
            [
                "name" => "Modelo",
                "id" => Constants::SPECIFICATION_MODEL
            ]
        ];
        $data['title'] = 'Tipos de especificaciones';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/crud_specification/abm_specifications_types.html.twig', $data);
    }

    #[Route("/{specification_type_id}/new", name: "secure_crud_specification_new", methods: ["GET", "POST"])]
    public function new(
        $specification_type_id,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        switch ($specification_type_id) {
            case Constants::SPECIFICATION_SCREEN_RESOLUTION:
                $data['specification'] = new ScreenResolution();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Resolución de pantalla';

                break;
            case Constants::SPECIFICATION_SCREEN_SIZE:
                $data['specification'] = new ScreenSize();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Tamaño de pantalla';

                break;
            case Constants::SPECIFICATION_CPU:
                $data['specification'] = new CPU();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: CPU';

                break;
            case Constants::SPECIFICATION_GPU:
                $data['specification'] = new GPU();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: GPU';

                break;
            case Constants::SPECIFICATION_MEMORY:
                $data['specification'] = new Memory();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Memoria';

                break;
            case Constants::SPECIFICATION_STORAGE:
                $data['specification'] = new Storage();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Almacenamiento';

                break;
            case Constants::SPECIFICATION_SO:
                $data['specification'] = new OS();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Sistema Operativo';

                break;
            case Constants::SPECIFICATION_COLOR:
                $data['specification'] = new Color();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Color';

                break;
            case Constants::SPECIFICATION_MODEL:
                $data['specification'] = new Model();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Nueva especificación de: Modelo';

                break;
        }
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_specification_type_index', 'title' => 'Tipos de especificaciones'),
            array('active' => true, 'title' => $data['title'])
        );

        $form = $this->createForm(SpecificationType::class, $data['specification'], ['entidad' => $specification_type_id]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data['specification']->setName($request->get('specification')['name']);
            $em->persist($data['specification']);
            $em->flush();

            return $this->redirectToRoute('secure_crud_specification_index', ['specification_type_id' => $specification_type_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/crud_specification/form_specifications.html.twig', $data);
    }

    #[Route("/{specification_type_id}/{specification_id}/edit", name: "secure_crud_specification_edit", methods: ["GET", "POST"])]
    public function edit(
        EntityManagerInterface $em,
        Request $request,
        ScreenResolutionRepository $screenResolutionRepository,
        ScreenSizeRepository $screenSizeRepository,
        CPURepository $cPURepository,
        GPURepository $gPURepository,
        MemoryRepository $memoryRepository,
        StorageRepository $storageRepository,
        OSRepository $oSRepository,
        ColorRepository $colorRepository,
        ModelRepository $modelRepository,
        $specification_type_id,
        $specification_id
    ): Response {
        switch ($specification_type_id) {
            case Constants::SPECIFICATION_SCREEN_RESOLUTION:
                $data['specification'] = $screenResolutionRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Resolución de pantalla';

                break;
            case Constants::SPECIFICATION_SCREEN_SIZE:
                $data['specification'] = $screenSizeRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Tamaño de pantalla';

                break;
            case Constants::SPECIFICATION_CPU:
                $data['specification'] = $cPURepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: CPU';

                break;
            case Constants::SPECIFICATION_GPU:
                $data['specification'] = $gPURepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: GPU';

                break;
            case Constants::SPECIFICATION_MEMORY:
                $data['specification'] = $memoryRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Memoria';

                break;
            case Constants::SPECIFICATION_STORAGE:
                $data['specification'] = $storageRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Almacenamiento';

                break;
            case Constants::SPECIFICATION_SO:
                $data['specification'] = $oSRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Sistema Operativo';

                break;
            case Constants::SPECIFICATION_COLOR:
                $data['specification'] = $colorRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Color';

                break;
            case Constants::SPECIFICATION_MODEL:
                $data['specification'] = $modelRepository->findOneBy(['id' => $specification_id]);
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Modelo';

                break;
        }
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_specification_type_index', 'title' => 'Tipos de especificaciones'),
            array('active' => true, 'title' => $data['title'])
        );
        $form = $this->createForm(SpecificationType::class, $data['specification'], ['name' => $data['specification']->getName(), 'old_id' => $data['specification']->getId(), 'entidad' => $specification_type_id]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['specification']->setName($request->get('specification')['name']);
            $em->persist($data['specification']);
            $em->flush();
            return $this->redirectToRoute('secure_crud_specification_index', ['specification_type_id' => $specification_type_id]);
        }
        $data['form'] = $form;
        return $this->renderForm('secure/crud_specification/form_specifications.html.twig', $data);
    }

    #[Route("/{specification_type_id}", name: "secure_crud_specification_index", methods: ["GET"])]
    public function specifications(
        $specification_type_id,
        ScreenResolutionRepository $screenResolutionRepository,
        ScreenSizeRepository $screenSizeRepository,
        CPURepository $cPURepository,
        GPURepository $gPURepository,
        MemoryRepository $memoryRepository,
        StorageRepository $storageRepository,
        OSRepository $oSRepository,
        ColorRepository $colorRepository,
        ModelRepository $modelRepository
    ): Response {

        switch ($specification_type_id) {
            case Constants::SPECIFICATION_SCREEN_RESOLUTION:
                $data['specifications'] = $screenResolutionRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Resolución de pantalla';

                break;
            case Constants::SPECIFICATION_SCREEN_SIZE:
                $data['specifications'] = $screenSizeRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Tamaño de pantalla';

                break;
            case Constants::SPECIFICATION_CPU:
                $data['specifications'] = $cPURepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: CPU';

                break;
            case Constants::SPECIFICATION_GPU:
                $data['specifications'] = $gPURepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: GPU';

                break;
            case Constants::SPECIFICATION_MEMORY:
                $data['specifications'] = $memoryRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Memoria';

                break;
            case Constants::SPECIFICATION_STORAGE:
                $data['specifications'] = $storageRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Almacenamiento';

                break;
            case Constants::SPECIFICATION_SO:
                $data['specifications'] = $oSRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Sistema Operativo';

                break;
            case Constants::SPECIFICATION_COLOR:
                $data['specifications'] = $colorRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Color';

                break;
            case Constants::SPECIFICATION_MODEL:
                $data['specifications'] = $modelRepository->findAll();
                $data['specification_type'] = ['id' => $specification_type_id];
                $data['title'] = 'Especificación: Modelo';

                break;
        }
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_specification_type_index', 'title' => 'Tipos de especificaciones'),
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/crud_specification/abm_specifications.html.twig', $data);
    }
}
