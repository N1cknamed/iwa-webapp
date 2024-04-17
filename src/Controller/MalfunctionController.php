<?php

namespace App\Controller;

use App\Entity\Malfunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MalfunctionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/malfunction', name: 'app_malfunction')]
    public function index(): Response
    {
        return $this->render('malfunction/index.html.twig', [
            'controller_name' => 'MalfunctionController',
        ]);
    }

    #[Route('/malfunction/{id}', name: 'malfunction_detail')]
    public function detail(int $id): Response
    {
        $malfunction = $this->entityManager
            ->getRepository(Malfunction::class)
            ->find($id);

        if (!$malfunction) {
            throw $this->createNotFoundException(
                'No malfunction found for id '.$id
            );
        }

        return $this->render('malfunction/detail.html.twig', [
            'malfunction' => $malfunction,
            'controller_name' => 'MalfunctionController',
            ]);
    }
}
