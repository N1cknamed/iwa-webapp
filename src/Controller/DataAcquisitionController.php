<?php

namespace App\Controller;

use App\Entity\Malfunction;
use App\Entity\TempCorrection;
use App\Entity\Weather;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\StationRepository;

class DataAcquisitionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dataacquisition', name: 'app_data_acquisition')]
    public function index(Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        $limit = 20; // how many results per page

        // Retrieve the country code from the request
        $countryCode = $request->query->get('countryCode');

        // Create the query builder
        $queryBuilder = $this->entityManager
            ->getRepository(Station::class)
            ->createQueryBuilder('s')
            ->leftJoin('s.geolocation', 'g')
            ->leftJoin('g.countryEntity', 'c')
            ->select('s', 'g', 'c')
            ->addSelect('s.name+0 as HIDDEN int_name') //hack to convert name string to integers for more logical ordering, since doctrine does not support casting
            ->orderBy('int_name');

        // If a country code is provided, add a where clause to the query
        if ($countryCode) {
            $queryBuilder->andWhere('c.country_code = :countryCode')
                ->setParameter('countryCode', $countryCode);
        }

        // Get the final query
        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit);

        $malfunctions = $this->entityManager
            ->getRepository(Malfunction::class)
            ->createQueryBuilder('m')
            ->where('m.status IN (:statuses)')
            ->setParameter('statuses', ['unresolved', 'in progress'])
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();

        return $this->render('data_acquisition/dataacq.html.twig', [
            'malfunctions' => $malfunctions,
            'stations' => $paginator,
            'controller_name' => 'DataAcquisitionController',
            'current_page' => $currentPage,
            'total_pages' => ceil(count($paginator) / $limit),
        ]);
    }

    #[Route('/dataacquisition/locations', name: 'app_station_locations')]
    public function getAllStations(): Response
    {
        $stations = $this->entityManager
            ->getRepository(Station::class)
            ->createQueryBuilder('s')
            ->select('s.name', 's.latitude', 's.longitude')
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($stations as $station) {
            $data[] = [
                'name' => $station['name'],
                'latitude' => $station['latitude'],
                'longitude' => $station['longitude'],
            ];
        }

        return $this->json($data);
    }

    #[Route('/dataacquisition/station/{name}', name: 'app_station_detail')]
    public function detail(string $name, StationRepository $stationRepository): Response
    {
        $station = $stationRepository->findWithWeatherData($name);

        if (!$station) {
            throw $this->createNotFoundException('The station does not exist');
        }

        $correctedTemps = $this->entityManager->getRepository(TempCorrection::class)->findBy(['STN' => $name]);

        return $this->render('data_acquisition/station.html.twig', [
            'station' => $station,
            'correctedTemps' => $correctedTemps,
            'controller_name' => 'DataAcquisitionController',
        ]);
    }

    #[Route('/dataacquisition/data', name: 'app_data_acquisition_data')]
    public function getAllData(Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);

        // Retrieve the form data
        $stationName = $request->query->get('stationName');
        $countryCode = $request->query->get('countryCode');
        $startDateTime = $request->query->get('startDateTime');
        $endDateTime = $request->query->get('endDateTime');

        // Create a query builder instance
        $queryBuilder = $this->entityManager
            ->getRepository(Weather::class)
            ->createQueryBuilder('w')
            ->leftJoin('w.station', 's')
            ->leftJoin('s.geolocation', 'g')
            ->leftJoin('g.countryEntity', 'c');

        // If a station name is provided, add a where clause to the query
        if ($stationName) {
            $queryBuilder->andWhere('s.name = :stationName')
                ->setParameter('stationName', $stationName);
        }

        // If a country code is provided, add a where clause to the query
        if ($countryCode) {
            $queryBuilder->andWhere('c.country_code = :countryCode')
                ->setParameter('countryCode', $countryCode);
        }

        // If a start date and time is provided, add a where clause to the query
        if ($startDateTime) {
            $queryBuilder->andWhere('w.DATE >= :startDateTime')
                ->setParameter('startDateTime', new \DateTime($startDateTime));
        }

        // If an end date and time is provided, add a where clause to the query
        if ($endDateTime) {
            $queryBuilder->andWhere('w.DATE <= :endDateTime')
                ->setParameter('endDateTime', new \DateTime($endDateTime));
        }

        $queryBuilder->orderBy('w.DATE', 'DESC');

        $limit1 = 15;

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginator->getQuery()
            ->setFirstResult($limit1 * ($currentPage - 1))
            ->setMaxResults($limit1);

        return $this->render('data_acquisition/data.html.twig', [
            'weatherData' => $paginator,
            'current_page' => $currentPage,
            'total_pages' => ceil(count($paginator) / $limit1),
            'limit' => $limit1,
            'controller_name' => 'DataAcquisitionController',
        ]);
    }
}