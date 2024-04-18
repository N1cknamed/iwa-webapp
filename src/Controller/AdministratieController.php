<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdministratieController extends AbstractController
{
    #[Route('/administratie', name: 'app_administratie')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('administratie/admin.html.twig', [
            'controller_name' => 'AdministratieController',
            'users' => $users,
        ]);
    }


    #[Route('/administratie/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('_profiler_home');
        }

        return $this->render('administratie/register.html.twig', [
            'controller_name' => 'AdministratieController',
            'registrationForm' => $form,
        ]);
    }


    #[Route("/remove/{id}", name: "app_remove_user")]
    public function removeUser($id, EntityManagerInterface $entityManager)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_administratie');
    }

    #[Route("/edit/{id}", name: "app_edit_user")]
    public function editUser($id, EntityManagerInterface $entityManager, Request $request)
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form = $this->createFormBuilder($user)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrator' => 'ROLE_ADMIN',
                    'Data Acquisition' => 'ROLE_DATA',
                    'Contractbeheerder' => 'ROLE_CONTRACT',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Update Account'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administratie');
        }

        return $this->render('administratie/edit.html.twig', [
            'controller_name' => 'AdministratieController',
            'editForm' => $form->createView(),
            'userEmail' => $user->getEmail(),
        ]);
    }
}