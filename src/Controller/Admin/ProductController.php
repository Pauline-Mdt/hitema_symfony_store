<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin', name: 'admin.')]
class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {}

    #[Route('/product', name: 'product.index')]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/product/form/{productId}', name: 'product.form')]
    public function form(int $productId = null): Response
    {
        $type = ProductType::class;
        $model = new Product();

        if (!empty($productId)) {
            $model = $this->productRepository->find($productId);
            $model->previousImage = $model->getImage();
        }

        $form = $this->createForm($type, $model);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($model->getImage() instanceof UploadedFile) {
                $filename = ByteString::fromRandom(32)->lower();
                $extension = $model->getImage()->guessExtension();

                $model->getImage()->move('images/products', "$filename.$extension");

                $model->setImage("$filename.$extension");

                if (!empty($productId)) {
                    unlink("images/products/{$model->previousImage}");
                }
            } else {
                $model->setImage($model->previousImage);
            }

            $this->entityManager->persist($model);
            $this->entityManager->flush();

            !empty($productId) ?
                $this->addFlash('message', 'Product updated')
                : $this->addFlash('message', 'Product created');

            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/remove/{productId}', name: 'product.remove')]
    public function remove(int $productId): Response
    {
        $model = $this->productRepository->find($productId);

        if (!empty($model)) {
            unlink("images/products/{$model->getImage()}");

            $this->entityManager->remove($model);
            $this->entityManager->flush();

            $this->addFlash('message', 'Product removed');
        } else {
            $this->addFlash('message', 'Product not found');
        }

        return $this->redirectToRoute('admin.product.index');
    }
}
