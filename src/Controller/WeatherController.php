<?php
// src/Controller/WeatherController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Weather;
use App\Entity\MissingValues;
use App\Entity\TempCorrection;

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

            $entityManager->beginTransaction();

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

                $this->extrapolateMissingValues($weather, $entityManager);

                $this->correctTemperature($weather, $entityManager);

            }

            $entityManager->flush();
            $entityManager->commit();

            return new Response('Success.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            $entityManager->rollback();

            // Log the exception
            //$this->get('logger')->error('Error occurred while saving weather data: ' . $e->getMessage());

            // Return an error response
            return new Response('An error occurred while saving weather data.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function extrapolateMissingValues(Weather $weather, $entityManager): void
    {
        $station = $weather->getSTN();

        $historicalData = $entityManager
            ->getRepository(Weather::class)
            ->findBy(['STN' => $station], ['DATE' => 'DESC', 'TIME' => 'DESC'], 30);
            print_r($historicalData);

        $valuesToExtrapolate = ['TEMP', 'DEWP', 'STP', 'SLP', 'VISIB', 'WDSP', 'PRCP', 'SNDP', 'CLDC', 'WNDDIR'];

        $missingValue = new MissingValues();
        $extrapolations = 0;

        foreach ($valuesToExtrapolate as $value) {
            if ($weather->{'get'.$value}() === null) {
                $sum = 0;
                $validCount = 0;
                foreach ($historicalData as $entry) {
                    $val = $entry->{'get'.$value}();
                    if ($val !== null) {
                        $sum += $val;
                        $validCount++;
                    }
                }
                if ($validCount > 0) {
                    $extrapolatedValue = $sum / $validCount;
                    $weather->{'set'.$value}($extrapolatedValue);
                    $missingValue->{'set'.$value}($extrapolatedValue);
                    $entityManager->persist($missingValue);
                    $extrapolations++;

                }
            }
        }
        if ($extrapolations > 0) {
            $missingValue->setSTN($weather->getSTN());
            $missingValue->setDATE($weather->getDATE());
            $missingValue->setTIME($weather->getTIME());
            $entityManager->persist($missingValue);

        }

        
    }

    private function correctTemperature(Weather $weather, $entityManager): void
    {
        $station = $weather->getSTN();

        $historicalData = $entityManager
            ->getRepository(Weather::class)
            ->findBy(['STN' => $station], ['DATE' => 'DESC', 'TIME' => 'DESC'], 30);

        if ($weather->getTEMP() !== null) {
            $sum = 0;
            $validCount = 0;
            $originalTemp = $weather->getTEMP();
            foreach ($historicalData as $entry) {
                $temp = $entry->getTEMP();
                if ($temp !== null) {
                    $sum += $temp;
                    $validCount++;
                }
            }
            if ($validCount > 0) {
                $averageTemp = $sum / $validCount;
                if ($weather->getTEMP() < 0.8 * $averageTemp) {
                    $correctedTemp = 0.8 * $averageTemp;
                    $weather->setTEMP($correctedTemp);

                    $tempcorrection = new TempCorrection();
                    $tempcorrection->setSTN($weather->getSTN());
                    $tempcorrection->setDATE($weather->getDATE());
                    $tempcorrection->setTIME($weather->getTIME());
                    $tempcorrection->setCorrectedTEMP($weather->getTEMP());
                    $tempcorrection->setOriginalTEMP($originalTemp);
                    $entityManager->persist($tempcorrection);


                } elseif ($weather->getTEMP() > 1.2 * $averageTemp) {
                    $correctedTemp = 1.2 * $averageTemp;
                    $weather->setTEMP($correctedTemp);

                    $tempcorrection = new TempCorrection();
                    $tempcorrection->setSTN($weather->getSTN());
                    $tempcorrection->setDATE($weather->getDATE());
                    $tempcorrection->setTIME($weather->getTIME());
                    $tempcorrection->setCorrectedTEMP($weather->getTEMP());
                    $tempcorrection->setOriginalTEMP($originalTemp);
                    $entityManager->persist($tempcorrection);
                }
            }
        }
    }
}
