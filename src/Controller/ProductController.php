<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {}

    #[Route('/products/page/{pageNumber}', name: 'product.index')]
    public function index(int $pageNumber = 1): Response
    {
        return $this->render('product/index.html.twig', [
            'numberOfProducts' => $this->productRepository->getNumberOfProducts()->getSingleScalarResult(),
            'products' => $this->productRepository->getProductsFromPage($pageNumber)->getResult(),
        ]);
    }

    #[Route('/product/{slug}', name: 'product.detail')]
    public function detail(string $slug): Response
    {
        return $this->render('product/detail.html.twig', [
            'product' => $this->productRepository->findOneBy(['slug' => $slug]),
        ]);
    }
}