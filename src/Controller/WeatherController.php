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
        $weatherData = $this->doctrine
            ->getRepository(Weather::class)
            ->findAll();
        return $this->render('weather/index.html.twig', [
            'weatherData' => $weatherData,
        ]);
    }

    #[Route('/weather/data', name: 'weather_data', methods: ['POST'])]
    public function receiveWeatherData(Request $request): Response
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        $entityManager = $this->doctrine->getManager();

        if ($data === null || !isset($data['WEATHERDATA']) || !is_array($data['WEATHERDATA'])) {
            return new Response('Invalid JSON format.', Response::HTTP_BAD_REQUEST);
        }
        try {

            foreach ($data['WEATHERDATA'] as $weatherData) {
                $weather = new Weather();
                $weather->setSTN(!empty($weatherData['STN']) ? (int)$weatherData['STN'] : null);
                $weather->setDATE(!empty($weatherData['DATE']) ? new \DateTime($weatherData['DATE']) : null);
                $weather->setTIME(!empty($weatherData['TIME']) ? new \DateTime($weatherData['TIME']) : null);
                $weather->setTEMP(!empty($weatherData['TEMP']) ? (float)$weatherData['TEMP'] : null);
                $weather->setDEWP(!empty($weatherData['DEWP']) ? (float)$weatherData['DEWP'] : null);
                $weather->setSTP(!empty($weatherData['STP']) ? (float)$weatherData['STP'] : null);
                $weather->setSLP(!empty($weatherData['SLP']) ? (float)$weatherData['SLP'] : null);
                $weather->setWDSP(!empty($weatherData['WDSP']) ? (float)$weatherData['WDSP'] : null);
                $weather->setVISIB(!empty($weatherData['VISIB']) ? (float)$weatherData['VISIB'] : null);
                $weather->setPRCP(!empty($weatherData['PRCP']) ? (float)$weatherData['PRCP'] : null);
                $weather->setFRSHTT(!empty($weatherData['FRSHTT']) ? (string)$weatherData['FRSHTT'] : null);
                $weather->setCLDC(!empty($weatherData['CLDC']) ? (float)$weatherData['CLDC'] : null);
                $weather->setSNDP(!empty($weatherData['SNDP']) ? (float)$weatherData['SNDP'] : null);
                $weather->setWNDDIR(!empty($weatherData['WNDDIR']) ? (int)$weatherData['WNDDIR'] : null);

                $entityManager->persist($weather);
            }

            $entityManager->flush();

            return new Response('Success.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            $entityManager->rollback();

            // Log the exception
            $this->get('logger')->error('Error occurred while saving weather data: ' . $e->getMessage());

            // Return an error response
            return new Response('An error occurred while saving weather data.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}