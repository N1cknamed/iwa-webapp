<?php

namespace App\Controller;

use App\Entity\Malfunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MissingValues;
use App\Entity\TempCorrection;
use App\Entity\Station;
use App\Entity\Message;


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

    #[Route('dataacquisition/malfunction/{name}/add-message', name: 'malfunction_add_message')]
    public function addMessage(Request $request, string $name, EntityManagerInterface $entityManager): Response
    {
        $station = $this->entityManager->getRepository(Station::class)->findOneBy(['name' => $name]);
        if (!$station) {
            throw $this->createNotFoundException('Station not found');
        }

        $message = $request->request->get('message');

        // Voeg de logica toe om het bericht op te slaan in de database, bijvoorbeeld:

        $messageEntity = new Message();
        $messageEntity->setStation($station);
        $messageEntity->setMessage($message);
        $entityManager->persist($messageEntity);
        $entityManager->flush();

        // Redirect naar de detailpagina
        return $this->redirectToRoute('malfunction_detail', ['name' => $name]);
    }

    #[Route('dataacquisition/malfunction/{name}/change-status', name: 'malfunction_change_status')]
    public function changeStatus(Request $request, string $name): Response
    {
        $station = $this->entityManager->getRepository(Station::class)->findOneBy(['name' => $name]);
        if (!$station) {
            throw $this->createNotFoundException('Station not found');
        }

        $status = $request->request->get('status');

        // Haal de storing op bij het station
        $malfunction = $this->entityManager->getRepository(Malfunction::class)->findOneBy(['station' => $station]);
        if (!$malfunction) {
            throw $this->createNotFoundException('Malfunction not found');
        }

        // Update de status van de storing
        $malfunction->setStatus($status);
        $this->entityManager->flush();

        // Redirect naar de detailpagina
        return $this->redirectToRoute('malfunction_detail', ['name' => $name]);
    }
}
