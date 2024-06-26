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

    #[Route('dataacquisition/malfunction/{page<\d+>?1}', name: 'app_malfunction')]
    public function index(Request $request, $page = 1): Response
    {
        $limit = 20; // Number of malfunctions per page
        $start = ($page - 1) * $limit;

        $repository = $this->entityManager->getRepository(Malfunction::class);

        $malfunctions = $repository->findBy(
            ['status' => ['unresolved', 'in progress', 'resolved']],
            null,
            $limit,
            $start
        );

        $count = $repository->count(['status' => ['unresolved', 'in progress', 'resolved']]);

        $totalPages = ceil($count / $limit);

        return $this->render('malfunction/malfunction.html.twig', [
            'malfunctions' => $malfunctions,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'controller_name' => 'MalfunctionController',
        ]);
    }

    #[Route('dataacquisition/malfunction/detail/{name}', name: 'malfunction_detail')]
    public function detail(string $name): Response
    {
        $station = $this->entityManager->getRepository(Station::class)->findOneBy(['name' => $name]);
        if (!$station) {
            throw $this->createNotFoundException('Station not found');
        }

        $malfunction = $this->entityManager->getRepository(Malfunction::class)->findOneBy(['station' => $station]);
        if (!$malfunction) {
            throw $this->createNotFoundException('Malfunction not found');
        }

        $missingValues = $this->entityManager->getRepository(MissingValues::class)->findBy(['STN' => $station->getName()]);
        $tempCorrections = $this->entityManager->getRepository(TempCorrection::class)->findBy(['STN' => $station->getName()]);

        return $this->render('malfunction/detail.html.twig', [
            'station' => $station,
            'malfunction' => $malfunction,
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

        $messageEntity = new Message();
        $messageEntity->setStation($station);
        $messageEntity->setMessage($message);
        $entityManager->persist($messageEntity);
        $entityManager->flush();

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

        $malfunction = $this->entityManager->getRepository(Malfunction::class)->findOneBy(['station' => $station]);
        if (!$malfunction) {
            throw $this->createNotFoundException('Malfunction not found');
        }

        $malfunction->setStatus($status);
        $this->entityManager->flush();

        return $this->redirectToRoute('malfunction_detail', ['name' => $name]);
    }
}
