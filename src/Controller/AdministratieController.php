<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdministratieController extends AbstractController
{
    #[Route('/administratie', name: 'app_administratie')]
    public function index(): Response
    {
        return $this->render('administratie/index.html.twig', [
            'controller_name' => 'AdministratieController',
        ]);
    }
}
