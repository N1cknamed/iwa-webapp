<?php

// Symfony Controller (src/Controller/ApiController.php)
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api_data')]

    public function fetchData()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://rest-api-url/data'); //placeholder voor onze daadwerkelijke api endpoint url

        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            $data = $response->getContent();
            return new Response($data);
        } else {
            return new Response('Failed to fetch data', $statusCode);
        }
    }
}
