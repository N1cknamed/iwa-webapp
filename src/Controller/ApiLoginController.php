<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, AuthenticationUtils $authenticationUtils, JWTTokenManagerInterface $JWTManager, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email']; // Changed from 'username' to 'email'
        $password = $data['password'];

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]); // Changed 'username' to 'email'

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new Response('Email or password is incorrect', Response::HTTP_UNAUTHORIZED); // Changed 'Username' to 'Email'
        }

        $token = $JWTManager->create($user);

        return new Response(json_encode(['token' => $token]));
    }
}