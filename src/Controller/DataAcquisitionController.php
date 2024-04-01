<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DataAcquisitionController extends AbstractController
{
    #[Route('/data/acquisition', name: 'app_data_acquisition')]
    public function index(): Response
    {
        return $this->render('data_acquisition/index.html.twig', [
            'controller_name' => 'DataAcquisitionController',
        ]);
    }
}
