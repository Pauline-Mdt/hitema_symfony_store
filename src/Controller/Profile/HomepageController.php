<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'profile.')]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage.index')]
    public function index(): Response
    {
        return $this->render('profile/homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
