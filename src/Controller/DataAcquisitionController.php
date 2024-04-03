<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class DataAcquisitionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dataacquisition', name: 'app_data_acquisition')]
    public function index(Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        $limit = 25;

        $query = $this->entityManager
            ->getRepository(Station::class)
            ->createQueryBuilder('s')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $this->render('data_acquisition/index.html.twig', [
            'stations' => $paginator,
            'controller_name' => 'DataAcquisitionController',
            'current_page' => $currentPage,
            'total_pages' => ceil(count($paginator) / $limit),
        ]);
    }
}