<?php

namespace App\Controller;

use App\Entity\Malfunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $malfunctions = $this->entityManager
            ->getRepository(Malfunction::class)
            ->findBy(['status' => ['unresolved', 'in progress']]);

        return $this->render('home/home.html.twig', [
            'malfunctions' => $malfunctions,
            'controller_name' => 'HomeController',
        ]);
    }
}