<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Malfunction;
use App\Entity\Subscription;
use App\Entity\User;
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

    public function getSubscriptionCount(): int
    {
        return $this->entityManager->getRepository(Subscription::class)->count([]);
    }

    public function getContractCount(): int
    {
        return $this->entityManager->getRepository(Contract::class)->count([]);
    }
    public function getUserCount(): int
    {
        return $this->entityManager->getRepository(User::class)->count([]);
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $malfunctions = $this->entityManager
            ->getRepository(Malfunction::class)
            ->findBy(['status' => ['unresolved', 'in progress', 'resolved']]);

        $subscriptionCount = $this->getSubscriptionCount();
        $contractCount = $this->getContractCount();
        $userCount = $this->getUserCount();


        return $this->render('home/home.html.twig', [
            'malfunctions' => $malfunctions,
            'subscriptionCount' => $subscriptionCount,
            'contractCount' => $contractCount,
            'userCount' => $userCount,
            'controller_name' => 'HomeController',
        ]);
    }
}