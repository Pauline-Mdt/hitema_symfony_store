<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin.')]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage.index')]
    public function index(): Response
    {
        return $this->render('admin/homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
