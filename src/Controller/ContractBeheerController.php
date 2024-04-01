<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContractBeheerController extends AbstractController
{
    #[Route('/contractbeheer', name: 'app_contract_beheer')]
    public function index(): Response
    {
        return $this->render('contract_beheer/index.html.twig', [
            'controller_name' => 'ContractBeheerController',
        ]);
    }
}
