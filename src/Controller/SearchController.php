<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {}

    #[Route('/search', name: 'search.index')]
    public function index(Request $request): Response
    {
        $searchValue = $request->query->get('searchValue');
        $products = [];

        if (!empty($searchValue)) {
            $products = $this->productRepository->search($searchValue)->getResult();
        } else {
            $this->addFlash('message', 'Attention le champ de recherche est vide. Merci de saisir une valeur.');
        }

        return $this->render('search/index.html.twig', [
            'searchValue' => $searchValue,
            'products' => $products,
        ]);
    }
}
