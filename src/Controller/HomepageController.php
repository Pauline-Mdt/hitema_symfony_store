<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {}

    #[Route('/', name: 'homepage.index')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'products' => $this->productRepository->findThreeRandom()->getResult(),
        ]);
    }
}