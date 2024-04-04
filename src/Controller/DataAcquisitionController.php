<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;

class DataAcquisitionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dataacquisition', name: 'app_data_acquisition')]
    public function index(): Response
    {
        $stations = $this->entityManager
            ->getRepository(Station::class)
            ->findBy([], null, 25); // Limit to 25 stations

        return $this->render('data_acquisition/index.html.twig', [
            'stations' => $stations,
            'controller_name' => 'DataAcquisitionController',
        ]);
    }
}