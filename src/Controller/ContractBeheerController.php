<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\Contract;
use App\Entity\ContractCoordinates;
Use App\Entity\ContractData;
use App\Form\SubscriptionFormType;
use App\Form\ContractFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContractBeheerController extends AbstractController
{
    #[Route('/contractbeheer', name: 'app_contract_beheer')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $subscriptions = $entityManager->getRepository(Subscription::class)->getActiveSubscriptions();
        $contracts = $entityManager->getRepository(Contract::class)->getActiveContracts();
 

        return $this->render('contract_beheer/index.html.twig', [
            'controller_name' => 'ContractBeheerController',
            'subscriptions' => $subscriptions,
            'contracts' => $contracts
        ]);
    }

    #[Route('/contractbeheer/subscription/add', name: 'app_add_subscription')]
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
        'SubscriptionForm' => $form,
        'name' => ''
    ]);
    }

    #[Route('/contractbeheer/subscription/edit/{id}', name: 'app_edit_subscription')]
    public function editSubscription($id, EntityManagerInterface $entityManager, Request $request)
    {
        $subscription = $entityManager->getRepository(Subscription::class)->find($id);

        if (!$subscription) {
            throw $this->createNotFoundException('No subscription found for id '.$id);
        }

        $form = $this->createFormBuilder($subscription)
            ->add('name_holder', TextType::class, ['data' => $subscription->getNameHolder()])
            ->add('date_start', DateType::class, ['data' => $subscription->getDateStart()])
            ->add('date_end', DateType::class, ['data' => $subscription->getDateEnd()])
            ->add('station', TextType::class, ['data' => $subscription->getStation()])
            ->add('save', SubmitType::class, ['label' => 'Update subscription'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('app_contract_beheer');
        }

        return $this->render('contract_beheer/editsubscription.html.twig', [
            'controller_name' => 'ContractBeheerController',
            'editForm' => $form->createView(),
            'name' => $subscription->getNameHolder()
        ]);
    }

    #[Route('/contractbeheer/subscription/remove/{id}', name: 'app_remove_subscription')]
    public function removeSubscription($id, EntityManagerInterface $entityManager)
    {
        $subscription = $entityManager->getRepository(Subscription::class)->find($id);

        if (!$subscription) {
            throw $this->createNotFoundException('No subscription found for id '.$id);
        }

        $entityManager->remove($subscription);
        $entityManager->flush();

        return $this->redirectToRoute('app_contract_beheer');
    }

    #[Route('/contractbeheer/subscription/{name}', name: 'app_subscriptions')]
    public function seeSubscriptions(EntityManagerInterface $entityManager, string $name): Response
    {
        $subscriptions = $entityManager->getRepository(Subscription::class)->getSubscriptions($name);

        if (!$subscriptions) {
            throw $this->createNotFoundException(
                'No subscriptions found for '.$name
            );
        }

        return $this->render('contract_beheer/subscriptions.html.twig', [
            'controller_name' => 'ConractBeheerController',
            'name' => $name,
            'subscriptions' => $subscriptions
    ]);
    }

    #[Route('/contractbeheer/subscription/{name}/add', name: 'app_add_subscription_to_name')]
    public function addSubscriptionToName(Request $request, EntityManagerInterface $entityManager, string $name): Response
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
        'SubscriptionForm' => $form,
        'name' => $name
    ]);
    }

    #[Route('/contractbeheer/contract/add', name: 'app_add_contract')]
    public function addContract(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contract = new Contract();
        $contractCoordinates = new ContractCoordinates();
        $contractData = new ContractData();

        $contract->setCoordinates($contractCoordinates);
        $contract->setData($contractData);

        $form = $this->createForm(ContractFormType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('app_contract_beheer');
        }
        
    return $this->render('contract_beheer/addcontract.html.twig', [
        'controller_name' => 'ContractBeheerController',
        'ContractForm' => $form,
        'name' => ''
    ]);
    }

    #[Route('/contractbeheer/contract/edit/{id}', name: 'app_edit_contract')]
    public function editContract($id, EntityManagerInterface $entityManager, Request $request)
    {
        $contract = $entityManager->getRepository(Contract::class)->find($id);

        if (!$contract) {
            throw $this->createNotFoundException('No contract found for id '.$id);
        }

        $form = $this->createFormBuilder($contract)
            ->add('name_holder', TextType::class, ['data' => $contract->getNameHolder()])
            ->add('date_start', DateType::class, ['data' => $contract->getStartDate()])
            ->add('date_end', DateType::class, ['data' => $contract->getDateEnd()])
            ->add('country_code', TextType::class, ['data' => $contract->getCountrycode()])
            ->add('region', TextType::class, ['data' => $contract->getRegion()])
            ->add('coordinates', CollectionType::class, ['data' => $contract->getCoordinates()])
            ->add('data', CollectionType::class, ['data' => $contract->getData()])
            ->add('save', SubmitType::class, ['label' => 'Update contract'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('app_contract_beheer');
        }

        return $this->render('contract_beheer/editcontract.html.twig', [
            'controller_name' => 'ContractBeheerController',
            'editForm' => $form->createView(),
            'name' => $contract->getNameHolder()
        ]);
    }

    #[Route('/contractbeheer/contract/remove/{id}', name: 'app_remove_contract')]
    public function removeContract($id, EntityManagerInterface $entityManager)
    {
        $contract = $entityManager->getRepository(Contract::class)->find($id);

        if (!$contract) {
            throw $this->createNotFoundException('No contract found for id '.$id);
        }

        $entityManager->remove($contract);
        $entityManager->flush();

        return $this->redirectToRoute('app_contract_beheer');
    }

    #[Route('/contractbeheer/contract/{name}', name: 'app_contracts')]
    public function seeContracts(EntityManagerInterface $entityManager, string $name): Response
    {
        $contracts = $entityManager->getRepository(Contract::class)->getContracts($name);

        if (!$contracts) {
            throw $this->createNotFoundException(
                'No subscriptions found for '.$name
            );
        }

        return $this->render('contract_beheer/contracts.html.twig', [
            'controller_name' => 'ConractBeheerController',
            'name' => $name,
            'contracts' => $contracts
    ]);
    }
    
    #[Route('/contractbeheer/contract/{name}/add', name: 'app_add_contract_to_name')]
    public function addContractToName(Request $request, EntityManagerInterface $entityManager, string $name): Response
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
        'ContractForm' => $form,
        'name' => $name
    ]);
    }

}
