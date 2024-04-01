<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfielController extends AbstractController
{
    #[Route('/profiel', name: 'app_profiel')]
    public function index(): Response
    {
        return $this->render('profiel/register.html.twig', [
            'controller_name' => 'ProfielController',
        ]);
    }
}
