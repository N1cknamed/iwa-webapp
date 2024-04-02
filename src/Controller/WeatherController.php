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

        foreach ($data['WEATHERDATA'] as $weatherData) {
            $weather = new Weather();
            $weather->setSTN($weatherData['STN']);
            $weather->setDate(new \DateTime($weatherData['DATE']));
            $weather->setTime(new \DateTime($weatherData['TIME']));
            $weather->setTemp($weatherData['TEMP']);
            $weather->setDEWP($weatherData['DEWP']);
            $weather->setSTP($weatherData['STP']);
            $weather->setSLP($weatherData['SLP']);
            $weather->setWDSP($weatherData['WDSP']);
            $weather->setVISIB($weatherData['VISIB']);
            $weather->setPRCP($weatherData['PRCP']);
            $weather->setFRSHTT($weatherData['FRSHTT']);
            $weather->setCLDC($weatherData['CLDC']);
            $weather->setSNDP($weatherData['SNDP']);
            $weather->setWNDDIR($weatherData['WNDDIR']);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($weather);
        }

        $entityManager->flush();

        return new Response('Succes.');
    }
}
