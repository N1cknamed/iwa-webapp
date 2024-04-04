<?php
// src/Controller/WeatherController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Weather;



class WeatherController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/weather', name: 'app_weather')]
    public function index()
    {
        // Haal de opgeslagen gegevens op uit de database
        $weatherData = $this->doctrine->getRepository(Weather::class)->findAll();

        return $this->render('weather/index.html.twig', [
            'weatherData' => $weatherData,
        ]);
    }

    #[Route('/weather', name: 'weather_data', methods: ['POST'])]
    public function receiveWeatherData(Request $request): Response
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        $entityManager = $this->doctrine->getManager();


        foreach ($data['WEATHERDATA'] as $weatherData) {
            $weather = new Weather();
            $weather->setSTN($weatherData['STN'] !== 'None' ? (int)$weatherData['STN'] : null);
            $weather->setDate(isset($weatherData['DATE']) !== 'None' ? new \DateTime($weatherData['DATE']) : null);
            $weather->setTime(isset($weatherData['TIME']) !== 'None' ? new \DateTime($weatherData['TIME']) : null);
            $weather->setTEMP($weatherData['TEMP'] !== 'None' ? (float)$weatherData['TEMP'] : null);
            $weather->setDEWP($weatherData['DEWP'] !== 'None' ? (float)$weatherData['DEWP'] : null);
            $weather->setSTP($weatherData['STP'] !== 'None' ? (float)$weatherData['STP'] : null);
            $weather->setSLP($weatherData['SLP'] !== 'None' ? (float)$weatherData['SLP'] : null);
            $weather->setWDSP($weatherData['WDSP'] !== 'None' ? (float)$weatherData['WDSP'] : null);
            $weather->setVISIB($weatherData['VISIB'] !== 'None' ? (float)$weatherData['VISIB'] : null);
            $weather->setPRCP($weatherData['PRCP'] !== 'None' ? (float)$weatherData['PRCP'] : null);
            $weather->setFRSHTT($weatherData['FRSHTT'] !== 'None' ? (string)$weatherData['FRSHTT'] : null);
            $weather->setCLDC($weatherData['CLDC'] !== 'None' ? (float)$weatherData['CLDC'] : null);
            $weather->setSNDP($weatherData['SNDP'] !== 'None' ? (float)$weatherData['SNDP'] : null);
            $weather->setWNDDIR($weatherData['WNDDIR'] !== 'None' ? (int)$weatherData['WNDDIR'] : null);

            $entityManager->persist($weather);
        }

        $entityManager->flush();

        return new Response('Succes.');
    }
}
