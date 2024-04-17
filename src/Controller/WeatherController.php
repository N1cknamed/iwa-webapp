<?php
// src/Controller/WeatherController.php
namespace App\Controller;

use App\Entity\Malfunction;
use App\Entity\Station;
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
    private $errorCount = [];

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
                $stn = !empty($weatherData['STN']) ? (int)$weatherData['STN'] : null;
                $stnString = (string) $stn;
                $station = $entityManager->getRepository(Station::class)->findOneBy(['name' => $stnString]);
                $weather->setStation($station);
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
        $station = $weather->getStation(); // Het station waarvoor we gegevens willen extrapoleren

        $historicalData = $entityManager
            ->getRepository(Weather::class)
            ->createQueryBuilder('w')
            ->where('w.station = :station')
            ->setParameter('station', $station)
            ->orderBy('w.DATE', 'DESC')
            ->orderBy('w.TIME', 'DESC')
            ->setMaxResults(30) // Laatste 30 records
            ->getQuery()
            ->getResult();

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

                    // Increment error count
                    $stationId = $weather->getStation()?->getName();
                    $hour = $weather->getDATE()->format('Y-m-d H');
                    $this->incrementErrorCount($stationId, $hour, $entityManager);
                }
            }
        }
        if ($extrapolations > 0) {
            $stationId = $weather->getStation()?->getName();
            $missingValue->setSTN($stationId);
            $missingValue->setDATE($weather->getDATE());
            $missingValue->setTIME($weather->getTIME());
            $entityManager->persist($missingValue);

        }

        
    }

    private function correctTemperature(Weather $weather, $entityManager): void
    {
        $station = $weather->getStation(); // Het station waarvoor we gegevens willen extrapoleren

        $historicalData = $entityManager
            ->getRepository(Weather::class)
            ->createQueryBuilder('w')
            ->where('w.station = :station')
            ->setParameter('station', $station)
            ->orderBy('w.DATE', 'DESC')
            ->orderBy('w.TIME', 'DESC')
            ->setMaxResults(30) // Laatste 30 records
            ->getQuery()
            ->getResult();

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
                $averageTempLowerBound = 0.8 * $averageTemp;
                $averageTempUpperBound = 1.2 * $averageTemp;

                if ($weather->getTEMP() < $averageTempLowerBound || $weather->getTEMP() > $averageTempUpperBound) {
                    $correctedTemp = $weather->getTEMP() < $averageTempLowerBound ? $averageTempLowerBound : $averageTempUpperBound;
                    $correctedTemp = round($correctedTemp, 1); // rond resultaat af op 1 decimaal
                    $weather->setTEMP($correctedTemp);

                    $tempcorrection = new TempCorrection();
                    $stationId = $weather->getStation()?->getName();
                    $tempcorrection->setSTN($stationId);
                    $tempcorrection->setDATE($weather->getDATE());
                    $tempcorrection->setTIME($weather->getTIME());
                    $tempcorrection->setCorrectedTEMP($weather->getTEMP());
                    $tempcorrection->setOriginalTEMP($originalTemp);
                    $entityManager->persist($tempcorrection);

                    // Increment error count
                    $stationId = $weather->getStation()?->getName();
                    $hour = $weather->getDATE()->format('Y-m-d H');
                    $this->incrementErrorCount($stationId, $hour, $entityManager);
                }
            }
        }
    }

    private function incrementErrorCount(string $stationId, string $hour, $entityManager)
    {
        $stationRepository = $entityManager->getRepository(Station::class);
        // Increment error count
        if (!isset($this->errorCount[$stationId])) {
            $this->errorCount[$stationId] = [];
        }
        if (!isset($this->errorCount[$stationId][$hour])) {
            $this->errorCount[$stationId][$hour] = 0;
        }
        $this->errorCount[$stationId][$hour]++;

        // Check if error count has reached 10
        if ($this->errorCount[$stationId][$hour] >= 10) {
            $malfunction = new Malfunction();
            $station = $stationRepository->findOneBy(['name' => $stationId]);
            $malfunction->setStation($station);
            $malfunction->setStatus('unresolved');
            $malfunction->setDATE(new \DateTime($hour . ':00:00')); // Start of the hour
            $entityManager->persist($malfunction);
        }
    }

    #[Route('/weather/detect-malfunctions', name: 'app_detect_malfunctions')]
    public function detectMalfunctionsInOldData(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $weatherRepository = $entityManager->getRepository(Weather::class);
        $stationRepository = $entityManager->getRepository(Station::class);

        $batchSize = 500; // Adjust this value based on your server's capabilities
        $offset = 0;

        while (($weatherData = $weatherRepository->findBy([], ['DATE' => 'DESC', 'TIME' => 'DESC'], $batchSize, $offset)) !== [] && $offset < 10000) {
            $errorCount = [];

            foreach ($weatherData as $weather) {
                $stationId = $weather->getStation()?->getName();
                $hour = $weather->getDATE()->format('Y-m-d H');

                // Increment error count
                if (!isset($errorCount[$stationId])) {
                    $errorCount[$stationId] = [];
                }
                if (!isset($errorCount[$stationId][$hour])) {
                    $errorCount[$stationId][$hour] = 0;
                }
                $errorCount[$stationId][$hour]++;

                // Check if error count has reached 10
                if ($errorCount[$stationId][$hour] >= 10) {
                    $malfunction = new Malfunction();
                    $station = $stationRepository->findOneBy(['name' => $stationId]);
                    $malfunction->setStation($station);
                    $malfunction->setStatus('unresolved');
                    $malfunction->setDATE(new \DateTime($hour . ':00:00')); // Start of the hour
                    $entityManager->persist($malfunction);

                    // Reset error count for this station and hour
                    $errorCount[$stationId][$hour] = 0;
                }
            }

            $entityManager->flush(); // Persist changes to the database
            $entityManager->clear(); // Detach all entities from the entity manager

            $offset += $batchSize; // Move the offset for the next batch
        }

        return new Response('Malfunction detection in old data completed.', Response::HTTP_OK);
    }
}
