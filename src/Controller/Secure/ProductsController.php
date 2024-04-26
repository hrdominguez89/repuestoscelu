<?php

namespace App\Controller\Secure;

use App\Constants\Constants;
use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\CPU;
use App\Entity\GPU;
use App\Entity\Memory;
use App\Entity\Model;
use App\Entity\OS;
use App\Entity\Product;
use App\Entity\ProductDiscount;
use App\Entity\ProductImages;
use App\Entity\ProductsSalesPoints;
use App\Entity\ScreenResolution;
use App\Entity\ScreenSize;
use App\Entity\Storage;
use App\Form\ProductDiscountType;
use App\Form\ProductTagType;
use App\Form\ProductType;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\CPURepository;
use App\Repository\GPURepository;
use App\Repository\MemoryRepository;
use App\Repository\ModelRepository;
use App\Repository\OSRepository;
use App\Repository\ProductDiscountRepository;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSubcategoryRepository;
use App\Repository\ProductTagRepository;
use App\Repository\ScreenResolutionRepository;
use App\Repository\ScreenSizeRepository;
use App\Repository\StorageRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Intervention\Image\ImageManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;



#[Route("/product")]
class ProductsController extends AbstractController
{

    private $pathImg = 'products';

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        BrandRepository $brandRepository,
        ProductTagRepository $productTagRepository,
        TagRepository $tagRepository,
        SubcategoryRepository $subcategoryRepository,
        ProductSubcategoryRepository $productSubcategoryRepository,
        ProductImagesRepository $productImagesRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->tagRepository = $tagRepository;
        $this->subcategoryRepository = $subcategoryRepository;
        $this->productSubcategoryRepository = $productSubcategoryRepository;
        $this->productTagRepository = $productTagRepository;
        $this->productImagesRepository = $productImagesRepository;
    }

    #[Route("/", name: "secure_product_index", methods: ["GET"])]
    public function index(ProductRepository $productRepository): Response
    {
        $data['products'] = $productRepository->findBy(['sale_point' => $this->getUser()]);
        $data['title'] = 'Mis productos';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/products/abm_products.html.twig', $data);
    }

    #[Route("/sale_point", name: "secure_product_sale_point", methods: ["GET"])]
    public function salePoint(ProductRepository $productRepository): Response
    {
        $data['sale_point'] = true;
        $data['products'] = $productRepository->findSalePointsProducts();
        $data['title'] = 'Productos de los puntos de venta';
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['breadcrumbs'] = array(
            array('active' => true, 'title' => $data['title'])
        );

        return $this->render('secure/products/abm_products.html.twig', $data);
    }

    #[Route("/new/{sale_point?}", name: "secure_product_new", methods: ["GET", "POST"])]
    public function new(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        Request $request,
        BrandRepository $brandRepository,
        ModelRepository $modelRepository,
        ColorRepository $colorRepository,
        ScreenResolutionRepository $screenResolutionRepository,
        CPURepository $CPURepository,
        GPURepository $GPURepository,
        MemoryRepository $memoryRepository,
        StorageRepository $storageRepository,
        ScreenSizeRepository $screenSizeRepository,
        OSRepository $OSRepository,
        SluggerInterface $slugger,
        SubcategoryRepository $subcategoryRepository,
        FileUploader $fileUploader,
        $sale_point = null
    ): Response {
        $data['user'] = $this->getUser();
        $data['title'] = 'Nuevo producto';
        $data['breadcrumbs'] = array(
            @$sale_point ? array('path' => 'secure_product_sale_point', 'title' => 'Productos de los puntos de venta') : array('path' => 'secure_product_index', 'title' => 'Mis productos'),
            array('active' => true, 'title' => $data['title'])
        );

        $data['brands'] = $brandRepository->findAll();
        $data['models'] = $modelRepository->findAll();
        $data['colors'] = $colorRepository->findAll();
        $data['screenResolutions'] = $screenResolutionRepository->findAll();
        $data['CPUs'] = $CPURepository->findAll();
        $data['GPUs'] = $GPURepository->findAll();
        $data['memories'] = $memoryRepository->findAll();
        $data['storages'] = $storageRepository->findAll();
        $data['screenSizes'] = $screenSizeRepository->findAll();
        $data['OSs'] = $OSRepository->findAll();

        $data['files_js'] = array('../uppy.min.js', '../select2.min.js', 'product/upload_files.js?v=' . rand(), 'product/product.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['product'] = new Product;
        if (!$sale_point) {
            $data['product']->setSalePoint($data['user']);
        }
        $form = $this->createForm(ProductType::class, $data['product'], ['user_role' => $sale_point ? $data['user']->getRole()->getId() : null]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['product']->setSubcategory($subcategoryRepository->findOneBy(['id' => (int)$request->get('product')['subcategory']]));

            $brand = $brandRepository->findOneBy(['name' => @$request->get('product')['brand']]);
            if (!$brand) {
                $brand = new Brand();
                $brand->setName($request->get('product')['brand']);
                $em->persist($brand);
            }
            $data['product']->setBrand($brand);

            $model = $modelRepository->findOneBy(['name' => @$request->get('product')['model']]);
            if (!$model) {
                $model = new Model();
                $model->setName($request->get('product')['model']);
                $em->persist($model);
            }
            $data['product']->setModel($model);

            $color = $colorRepository->findOneBy(['name' => @$request->get('product')['color']]);
            if (!$color) {
                $color = new Color();
                $color->setName($request->get('product')['color']);
                $em->persist($color);
            }
            $data['product']->setColor($color);

            $screenResolution = $screenResolutionRepository->findOneBy(['name' => @$request->get('product')['screenResolution']]);
            if (!$screenResolution) {
                $screenResolution = new ScreenResolution();
                $screenResolution->setName($request->get('product')['screenResolution']);
                $em->persist($screenResolution);
            }
            $data['product']->setScreenResolution($screenResolution);

            $CPU = $CPURepository->findOneBy(['name' => @$request->get('product')['CPU']]);
            if (!$CPU) {
                $CPU = new CPU();
                $CPU->setName($request->get('product')['CPU']);
                $em->persist($CPU);
            }
            $data['product']->setCPU($CPU);

            $GPU = $GPURepository->findOneBy(['name' => @$request->get('product')['GPU']]);
            if (!$GPU) {
                $GPU = new GPU();
                $GPU->setName($request->get('product')['GPU']);
                $em->persist($GPU);
            }
            $data['product']->setGPU($GPU);

            $memory = $memoryRepository->findOneBy(['name' => @$request->get('product')['memory']]);
            if (!$memory) {
                $memory = new Memory();
                $memory->setName($request->get('product')['memory']);
                $em->persist($memory);
            }
            $data['product']->setMemory($memory);

            $storage = $storageRepository->findOneBy(['name' => @$request->get('product')['storage']]);
            if (!$storage) {
                $storage = new Storage();
                $storage->setName($request->get('product')['storage']);
                $em->persist($storage);
            }
            $data['product']->setStorage($storage);

            $screenSize = $screenSizeRepository->findOneBy(['name' => @$request->get('product')['screenSize']]);
            if (!$screenSize) {
                $screenSize = new ScreenSize();
                $screenSize->setName($request->get('product')['screenSize']);
                $em->persist($screenSize);
            }
            $data['product']->setScreenSize($screenSize);

            $OS = $OSRepository->findOneBy(['name' => @$request->get('product')['OS']]);
            if (!$OS) {
                $OS = new OS();
                $OS->setName($request->get('product')['OS']);
                $em->persist($OS);
            }
            $data['product']->setOS($OS);


            if ($data['user']->getRole()->getId() === Constants::ROLE_SUPER_ADMIN && !$sale_point) {
                $sales_points = $userRepository->findBy(['role' => Constants::ROLE_SUCURSAL]);
                foreach ($sales_points as $sale_point_object) {
                    $product_sale_point = new ProductsSalesPoints();
                    $product_sale_point->setProduct($data['product']);
                    $product_sale_point->setSalePoint($sale_point_object);
                    $em->persist($product_sale_point);
                }
            } else {
                $product_sale_point = new ProductsSalesPoints();
                $product_sale_point->setProduct($data['product']);
                $product_sale_point->setSalePoint($data['product']->getSalePoint());
                $em->persist($product_sale_point);
            }
            $em->persist($data['product']);
            $productNameSlug = $slugger->slug($form->get('name')->getData());
            $imagesFilesBase64 = $form->get('images')->getData();
            $imagesFiles = explode('*,*', $imagesFilesBase64);

            if ($imagesFiles[0]) {
                if ($imagesFiles[0]) {
                    try {
                        $indexImage = !$data['product']->getImage()[0] ? 0 : 1;
                        foreach ($imagesFiles as $imageFile) {
                            $images = new ProductImages;
                            if ($indexImage == 0) {
                                $images->setPrincipal(true);
                                $indexImage++;
                            }
                            $file = base64_decode(explode(',', $imageFile)[1]);

                            // Crear instancia de UploadedFile a partir del contenido base64
                            $uploadedFile = new UploadedFile(tempnam(sys_get_temp_dir(), ''), 'filename.jpg');
                            file_put_contents($uploadedFile->getPathname(), $file);

                            // Llamar al método upload del servicio FileUploader para la imagen original
                            $imageFileName = $fileUploader->upload($uploadedFile, $productNameSlug, $this->pathImg);
                            $images->setImage($_ENV['AWS_S3_URL'] . $imageFileName);

                            /**
                             * 
                             * ARREGLAR SUBIDA DE IMAGEN EN MINIATURA.
                             * 
                             */

                            // // Escalar la imagen proporcionalmente
                            // // Instancia directa de ImageManager
                            // // $imageManager = new ImageManager();
                            // $tmpImage = $this->imageManager->make($file);
                            // $scaledImage = $tmpImage->resize(200, null, function ($constraint) {
                            //     $constraint->aspectRatio();
                            //     $constraint->upsize();
                            // });

                            // // Guardar la imagen redimensionada en un archivo temporal
                            // $tmpImagePath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                            // $scaledImage->save($tmpImagePath);

                            // // Crear instancia de UploadedFile a partir del archivo temporal
                            // $uploadedScaledImage = new UploadedFile($tmpImagePath, 'filename.jpg');

                            // // Llamar al método upload del servicio FileUploader para la imagen redimensionada
                            // $scaledImageFileName = $fileUploader->upload($uploadedScaledImage, $productNameSlug, $this->pathImg);
                            // $images->setImgThumbnail($_ENV['AWS_S3_URL'] . $scaledImageFileName);

                            // // Eliminar el archivo temporal
                            // unlink($tmpImagePath);


                            /**
                             * 
                             * FIN DE SUBIDA IMAGEN MINIATURA
                             * 
                             */

                            // Eliminar el archivo temporal de la imagen original
                            unlink($uploadedFile->getPathname());

                            $images->setProduct($data['product']);
                            $em->persist($images);
                        }
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                        die();
                    }
                }
            }
            $em->flush();
            $em->refresh($data['product']);
            if (@$sale_point) {
                return $this->redirectToRoute('secure_product_sale_point');
            }
            return $this->redirectToRoute('secure_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_products.html.twig', $data);
    }

    #[Route("/{id}/edit/{sale_point?}", name: "secure_product_edit", methods: ["GET", "POST"])]
    public function edit(
        EntityManagerInterface $em,
        $id,
        Request $request,
        BrandRepository $brandRepository,
        ModelRepository $modelRepository,
        ColorRepository $colorRepository,
        ScreenResolutionRepository $screenResolutionRepository,
        CPURepository $CPURepository,
        GPURepository $GPURepository,
        MemoryRepository $memoryRepository,
        StorageRepository $storageRepository,
        ScreenSizeRepository $screenSizeRepository,
        OSRepository $OSRepository,
        SluggerInterface $slugger,
        ProductRepository $productRepository,
        SubcategoryRepository $subcategoryRepository,
        FileUploader $fileUploader,
        $sale_point = null): Response
    {
        $data['title'] = 'Editar producto';
        $data['breadcrumbs'] = array(
            @$sale_point ? array('path' => 'secure_product_sale_point', 'title' => 'Productos de los puntos de venta') : array('path' => 'secure_product_index', 'title' => 'Mis productos'),
            array('active' => true, 'title' => $data['title'])
        );

        $data['brands'] = $brandRepository->findAll();
        $data['models'] = $modelRepository->findAll();
        $data['colors'] = $colorRepository->findAll();
        $data['screenResolutions'] = $screenResolutionRepository->findAll();
        $data['CPUs'] = $CPURepository->findAll();
        $data['GPUs'] = $GPURepository->findAll();
        $data['memories'] = $memoryRepository->findAll();
        $data['storages'] = $storageRepository->findAll();
        $data['screenSizes'] = $screenSizeRepository->findAll();
        $data['OSs'] = $OSRepository->findAll();

        $data['files_js'] = array('../uppy.min.js', '../pgwslideshow.min.js', '../select2.min.js', 'product/upload_files.js?v=' . rand(), 'product/product.js?v=' . rand());
        $data['files_css'] = array('uppy.min.css', 'pgwslideshow.min.css', 'select2.min.css', 'select2-bootstrap4.min.css');
        $data['product'] = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $data['product']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['product']->setSubcategory($subcategoryRepository->findOneBy(['id' => (int)$request->get('product')['subcategory']]));

            $brand = $brandRepository->findOneBy(['name' => @$request->get('product')['brand']]);
            if (!$brand) {
                $brand = new Brand();
                $brand->setName($request->get('product')['brand']);
                $em->persist($brand);
            }
            $data['product']->setBrand($brand);

            $model = $modelRepository->findOneBy(['name' => @$request->get('product')['model']]);
            if (!$model) {
                $model = new Model();
                $model->setName($request->get('product')['model']);
                $em->persist($model);
            }
            $data['product']->setModel($model);

            $color = $colorRepository->findOneBy(['name' => @$request->get('product')['color']]);
            if (!$color) {
                $color = new Color();
                $color->setName($request->get('product')['color']);
                $em->persist($color);
            }
            $data['product']->setColor($color);

            $screenResolution = $screenResolutionRepository->findOneBy(['name' => @$request->get('product')['screenResolution']]);
            if (!$screenResolution) {
                $screenResolution = new ScreenResolution();
                $screenResolution->setName($request->get('product')['screenResolution']);
                $em->persist($screenResolution);
            }
            $data['product']->setScreenResolution($screenResolution);

            $CPU = $CPURepository->findOneBy(['name' => @$request->get('product')['CPU']]);
            if (!$CPU) {
                $CPU = new CPU();
                $CPU->setName($request->get('product')['CPU']);
                $em->persist($CPU);
            }
            $data['product']->setCPU($CPU);

            $GPU = $GPURepository->findOneBy(['name' => @$request->get('product')['GPU']]);
            if (!$GPU) {
                $GPU = new GPU();
                $GPU->setName($request->get('product')['GPU']);
                $em->persist($GPU);
            }
            $data['product']->setGPU($GPU);

            $memory = $memoryRepository->findOneBy(['name' => @$request->get('product')['memory']]);
            if (!$memory) {
                $memory = new Memory();
                $memory->setName($request->get('product')['memory']);
                $em->persist($memory);
            }
            $data['product']->setMemory($memory);

            $storage = $storageRepository->findOneBy(['name' => @$request->get('product')['storage']]);
            if (!$storage) {
                $storage = new Storage();
                $storage->setName($request->get('product')['storage']);
                $em->persist($storage);
            }
            $data['product']->setStorage($storage);

            $screenSize = $screenSizeRepository->findOneBy(['name' => @$request->get('product')['screenSize']]);
            if (!$screenSize) {
                $screenSize = new ScreenSize();
                $screenSize->setName($request->get('product')['screenSize']);
                $em->persist($screenSize);
            }
            $data['product']->setScreenSize($screenSize);

            $OS = $OSRepository->findOneBy(['name' => @$request->get('product')['OS']]);
            if (!$OS) {
                $OS = new OS();
                $OS->setName($request->get('product')['OS']);
                $em->persist($OS);
            }
            $data['product']->setOS($OS);

            $productNameSlug = $slugger->slug($form->get('name')->getData());
            $imagesFilesBase64 = $form->get('images')->getData();
            $imagesFiles = explode('*,*', $imagesFilesBase64);

            if ($imagesFiles[0]) {
                try {
                    $indexImage = !$data['product']->getImage()[0] ? 0 : 1;
                    foreach ($imagesFiles as $imageFile) {
                        $images = new ProductImages;
                        if ($indexImage == 0) {
                            $images->setPrincipal(true);
                            $indexImage++;
                        }
                        $file = base64_decode(explode(',', $imageFile)[1]);

                        // Crear instancia de UploadedFile a partir del contenido base64
                        $uploadedFile = new UploadedFile(tempnam(sys_get_temp_dir(), ''), 'filename.jpg');
                        file_put_contents($uploadedFile->getPathname(), $file);

                        // Llamar al método upload del servicio FileUploader para la imagen original
                        $imageFileName = $fileUploader->upload($uploadedFile, $productNameSlug, $this->pathImg);
                        $images->setImage($_ENV['AWS_S3_URL'] . $imageFileName);

                        /**
                         * 
                         * ARREGLAR SUBIDA DE IMAGEN EN MINIATURA.
                         * 
                         */

                        // // Escalar la imagen proporcionalmente
                        // // Instancia directa de ImageManager
                        // // $imageManager = new ImageManager();
                        // $tmpImage = $this->imageManager->make($file);
                        // $scaledImage = $tmpImage->resize(200, null, function ($constraint) {
                        //     $constraint->aspectRatio();
                        //     $constraint->upsize();
                        // });

                        // // Guardar la imagen redimensionada en un archivo temporal
                        // $tmpImagePath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                        // $scaledImage->save($tmpImagePath);

                        // // Crear instancia de UploadedFile a partir del archivo temporal
                        // $uploadedScaledImage = new UploadedFile($tmpImagePath, 'filename.jpg');

                        // // Llamar al método upload del servicio FileUploader para la imagen redimensionada
                        // $scaledImageFileName = $fileUploader->upload($uploadedScaledImage, $productNameSlug, $this->pathImg);
                        // $images->setImgThumbnail($_ENV['AWS_S3_URL'] . $scaledImageFileName);

                        // // Eliminar el archivo temporal
                        // unlink($tmpImagePath);


                        /**
                         * 
                         * FIN DE SUBIDA IMAGEN MINIATURA
                         * 
                         */

                        // Eliminar el archivo temporal de la imagen original
                        unlink($uploadedFile->getPathname());

                        $images->setProduct($data['product']);
                        $em->persist($images);
                    }
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                    die();
                }
            }
            $em->flush();
            if (@$sale_point) {
                return $this->redirectToRoute('secure_product_sale_point');
            }
            return $this->redirectToRoute('secure_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_products.html.twig', $data);
    }

    #[Route("/{product_id}/discount", name: "secure_product_discount", methods: ["GET", "POST"])]
    public function discount(EntityManagerInterface $em, $product_id, Request $request, ProductRepository $productRepository, ProductDiscountRepository $productDiscountRepository): Response
    {
        $data['title'] = 'Descuento de producto';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('table_full_buttons.js?v=' . rand());
        $data['product'] = $productRepository->find($product_id);
        $data['products_discount'] = $productDiscountRepository->findBy(['product' => $product_id]);
        $data['new_product_discount'] = new ProductDiscount;
        $form = $this->createForm(ProductDiscountType::class, $data['new_product_discount']);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $em;
            $now = new \DateTime();
            if ($form->get('start_date')->getData() > $now) {
                $data['new_product_discount']->setActive(false);
            }
            $data['new_product_discount']->setProduct($data['product']);
            $data['new_product_discount']->setCreatedByUser($this->getUser());
            $entityManager->persist($data['new_product_discount']);

            $productDiscountRepository->disableAllDiscountProduct($product_id);

            $entityManager->flush();
            $this->addFlash(
                'message',
                [
                    "alert-color" => "success",
                    "title" => 'Nuevo descuento generado.',
                    "message" => 'Se generó un nuevo descuento correctamente.'
                ]
            );
            return $this->redirectToRoute('secure_product_discount', ['product_id' => $product_id]);
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_discount.html.twig', $data);
    }

    #[Route("/discount/{discount_id}/disable", name: "secure_product_discount_disable", methods: ["GET"])]
    public function disableDiscount(EntityManagerInterface $em, $discount_id, ProductDiscountRepository $productDiscountRepository): Response
    {
        $data['products_discount'] = $productDiscountRepository->find($discount_id);
        $entityManager = $em;
        $data['products_discount']->setActive(false);
        $entityManager->persist($data['products_discount']);
        $entityManager->flush();
        $this->addFlash(
            'message',
            [
                "alert-color" => "success",
                "title" => 'Descuento desactivado.',
                "message" => 'Se desactivó el descuento correctamente.'
            ]
        );
        return $this->redirectToRoute('secure_product_discount', ['product_id' => $data['products_discount']->getProduct()->getId()]);
    }

    #[Route("/{product_id}/tag", name: "secure_product_tag", methods: ["GET", "POST"])]
    public function tag(EntityManagerInterface $em, $product_id, Request $request, ProductRepository $productRepository): Response
    {
        $data['title'] = 'Etiqueta';
        $data['breadcrumbs'] = array(
            array('path' => 'secure_product_index', 'title' => 'Productos'),
            array('active' => true, 'title' => $data['title'])
        );
        $data['files_js'] = array('../select2.min.js', 'product/tag.js?v=' . rand());
        $data['files_css'] = array('select2.min.css', 'select2-bootstrap4.min.css');
        $data['product'] = $productRepository->find($product_id);
        $form = $this->createForm(ProductTagType::class, $data['product']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('tag_expires')->getData()) {
                $data['product']->setTagExpires(false);
                $data['product']->setTagExpirationDate(null);
            }

            $entityManager = $em;

            $entityManager->persist($data['product']);

            $entityManager->flush();

            return $this->redirectToRoute('secure_product_index');
        }

        $data['form'] = $form;
        return $this->renderForm('secure/products/form_tag.html.twig', $data);
    }

    #[Route("/consultFreeSku/{sku}", name: "secure_consult_free_sku", methods: ["GET"])]
    public function consultFreeSku($sku, ProductRepository $productRepository, Request $request): Response
    {
        $data['data'] = $productRepository->findFreeSku($sku, $request->get('product_id'));
        if (!$data['data']) {
            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'El SKU ya se encuentra registrado para ver el producto haga <a target="_blank" href="/secure/product/' . $data['data']['id'] . '/edit">click aquí</a>';
        }
        return new JsonResponse($data);
    }

    #[Route("/deleteImageProduct", name: "secure_delete_image_product", methods: ["POST"])]
    public function deleteImageProduct(EntityManagerInterface $em, ProductImagesRepository $productImagesRepository, Request $request): Response
    {
        $em = $em;
        $image_id = $request->get('image_id');
        $principal_image = (bool)$request->get('principal_image');
        $imageObject = $productImagesRepository->find($image_id);
        if ($imageObject) {
            if ($principal_image) {
                $next_image_not_principal = $productImagesRepository->getImageNotPrincipal($imageObject->getProduct()->getId());
                if ($next_image_not_principal) {
                    $next_image_principal = $productImagesRepository->find($next_image_not_principal[0]['id']);
                    $next_image_principal->setPrincipal(true);
                    $em->persist($next_image_principal);
                }
            }

            $path = explode($_ENV['AWS_S3_URL'] . '/', $imageObject->getImage())[1];
            $paththumbnail = explode($_ENV['AWS_S3_URL'] . '/', $imageObject->getImgThumbnail())[1];


            $em->remove($imageObject);
            $em->flush();


            $s3 = new S3Client([
                'region' => $_ENV['AWS_S3_BUCKET_REGION'],
                'version' => 'latest',
                'credentials' => [
                    'key' => $_ENV['AWS_S3_ACCESS_ID'],
                    'secret' => $_ENV['AWS_S3_ACCESS_SECRET'],
                ],
            ]);

            $s3->deleteObject([
                'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                'Key'    => $path
            ]);

            $s3->deleteObject([
                'Bucket' => $_ENV['AWS_S3_BUCKET_NAME'],
                'Key'    => $paththumbnail
            ]);

            $data['status'] = true;
            $data['new_principal_image_id'] = $principal_image ? ($next_image_not_principal ? $next_image_not_principal[0]['id'] : false) : false;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontro la imagen que se desea eliminar';
        }
        return new JsonResponse($data);
    }

    #[Route("/newPrincipalImage", name: "secure_new_principal_image_product", methods: ["POST"])]
    public function newPrincipalImage(EntityManagerInterface $em, ProductImagesRepository $productImagesRepository, Request $request): Response
    {

        $old_principal_image_id = $request->get('old_principal_image_id');
        $new_principal_image_id = $request->get('new_principal_image_id');

        $imageObjectOldPrincipal = $productImagesRepository->find($old_principal_image_id);
        $imageObjectNewPrincipal = $productImagesRepository->find($new_principal_image_id);

        if ($imageObjectOldPrincipal && $imageObjectNewPrincipal) {

            $imageObjectOldPrincipal->setPrincipal(false);
            $imageObjectNewPrincipal->setPrincipal(true);

            $em->persist($imageObjectOldPrincipal);
            $em->persist($imageObjectNewPrincipal);
            $em->flush();

            $data['status'] = true;
        } else {
            $data['status'] = false;
            $data['message'] = 'No se encontro la imagen.';
        }
        return new JsonResponse($data);
    }


    #[Route("/updateVisible/product", name: "secure_product_update_visible", methods: ["post"])]
    public function updateVisible(EntityManagerInterface $em, Request $request, ProductRepository $ProductRepository): Response
    {
        $id = (int)$request->get('id');
        $visible = $request->get('visible');


        $entity_object = $ProductRepository->find($id);

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
