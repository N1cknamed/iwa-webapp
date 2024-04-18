<?php

namespace App\Controller;

use App\Entity\Malfunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MissingValues;
use App\Entity\TempCorrection;
use App\Entity\Station;


class MalfunctionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('dataacquisition/malfunction', name: 'app_malfunction')]
    public function index(): Response
    {
        $malfunctions = $this->entityManager
        ->getRepository(Malfunction::class)
        ->findBy(['status' => ['unresolved', 'in progress']]);

        return $this->render('malfunction/malfunction.html.twig', [
            'malfunctions' => $malfunctions,
            'controller_name' => 'MalfunctionController',
        ]);
    }

    #[Route('dataacquisition/malfunction/{name}', name: 'malfunction_detail')]
    public function detail(string $name): Response
    {
        $station = $this->entityManager->getRepository(Station::class)->findOneBy(['name' => $name]);
        if (!$station) {
            throw $this->createNotFoundException('Station not found');
        }
    
        $missingValues = $this->entityManager->getRepository(MissingValues::class)->findBy(['STN' => $station->getName()]);
        $tempCorrections = $this->entityManager->getRepository(TempCorrection::class)->findBy(['STN' => $station->getName()]);
    
        return $this->render('malfunction/detail.html.twig', [
            'station' => $station,
            'missingValues' => $missingValues,
            'tempCorrections' => $tempCorrections,
            'controller_name' => 'MalfunctionController',
        ]);
    }
}
