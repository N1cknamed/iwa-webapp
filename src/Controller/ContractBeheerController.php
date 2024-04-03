<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\Contract;
use App\Form\SubscriptionFormType;
use App\Form\ContractFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/contractbeheer/addsubscription', name: 'app_add_subscription')]
    public function addSubscription(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($subscription);
            $entityManager->flush();

            return $this->redirectToRoute('app_contract_beheer');
        }
        
    return $this->render('contract_beheer/addsubscription.html.twig', [
        'controller_name' => 'ContractBeheerController',
        'SubscriptionForm' => $form
    ]);
    }

    #[Route('/contractbeheer/addcontract', name: 'app_add_contract')]
    public function addContract(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractFormType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('app_contract_beheer');
        }
        
    return $this->render('contract_beheer/addcontract.html.twig', [
        'controller_name' => 'ContractBeheerController',
        'ContractForm' => $form
    ]);
    }

}
