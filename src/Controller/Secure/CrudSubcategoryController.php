<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Category;
use App\Entity\Subcategory;
use App\Form\SubcategoryType;
use App\Helpers\FileUploader;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/subcategory")]
class CrudSubcategoryController extends AbstractController
{
    #[Route("/", name: "secure_crud_subcategory_index", methods: ["GET"])]
    public function index(SubcategoryRepository $subcategoryRepository): Response
    {
        $data['subcategories'] = $subcategoryRepository->listSubcategories();
        $data['title'] = 'Subcategorías';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );
        return $this->render('secure/crud_subcategory/abm_subcategory.html.twig', $data);
    }

    #[Route("/new", name: "secure_crud_subcategory_new", methods: ["GET", "POST"])]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $data['title'] = 'Nueva subcategoría';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_subcategory_index', 'title' => 'Subcategorías'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['subcategory'] = new Subcategory();

        $form = $this->createForm(SubcategoryType::class, $data['subcategory']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $em;
            $entityManager->persist($data['subcategory']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_subcategory_index');
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_subcategory/form_subcategory.html.twig', $data);
    }


    #[Route("/{subcategory_id}/edit", name: "secure_crud_subcategory_edit", methods: ["GET", "POST"])]
    public function edit(EntityManagerInterface $em, $subcategory_id, Request $request, SubcategoryRepository $subcategoryRepository): Response
    {
        $data['title'] = 'Editar subcategoría';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_crud_subcategory_index', 'title' => 'Subcategorías'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['subcategory'] = $subcategoryRepository->find($subcategory_id);

        $form = $this->createForm(SubcategoryType::class, $data['subcategory']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data['subcategory']->setCategory($data['subcategory']->getCategory());
            $entityManager = $em;
            $entityManager->persist($data['subcategory']);
            $entityManager->flush();

            return $this->redirectToRoute('secure_crud_subcategory_index');
        }
        $data['form'] = $form;

        return $this->renderForm('secure/crud_subcategory/form_subcategory.html.twig', $data);
    }

    #[Route("/getSubcategories/{category_id}", name: "secure_get_categories", methods: ["GET"])]
    public function getSubcategories($category_id, SubcategoryRepository $subcategoryRepository): Response
    {
        $data['data'] = $subcategoryRepository->getSubcategoriesVisiblesByCategory($category_id);
        if ($data['data']) {
            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontraron subcategorias con el id indicado';
        }
        return new JsonResponse($data);
    }

    #[Route("/updateVisible/subcategory", name: "secure_subcategory_update_visible", methods: ["post"])]
    public function updateVisible(EntityManagerInterface $em, Request $request, SubcategoryRepository $subcategoryRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');


        $entity_object = $subcategoryRepository->find($id);

        if ($visible == 'on') {
            $entity_object->setVisible(false);
            $data['visible'] = false;
        } else {
            $entity_object->setVisible(true);
            $data['visible'] = true;
        }

        $entityManager = $em;
        $entityManager->persist($entity_object);
        $entityManager->flush();

        $data['status'] = true;

        return new JsonResponse($data);
    }
}
