<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $categoryRepository)
    {}

    #[Route('/categories', name: 'category.index')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'categoriesInfos' => $this->categoryRepository->getNumberOfProductsByCategory()->getScalarResult(),
        ]);
    }

    #[Route('/category/{slug}', name: 'category.products')]
    public function products(string $slug): Response
    {
        return $this->render('category/products.html.twig', [
            'products' => $this->categoryRepository->getProductsFromCategory($slug)->getSingleResult()->getProducts(),
        ]);
    }

}