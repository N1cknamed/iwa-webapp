<?php

namespace App\Controller;

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
        $limit = 15; // how many results per page

        $query = $this->entityManager
            ->getRepository(Station::class)
            ->createQueryBuilder('s')
            ->leftJoin('s.geolocation', 'g') // join with Geolocation entity
            ->leftJoin('g.countryEntity', 'c') // join with Country entity
            ->select('s', 'g', 'c') // select all fields from Station, Geolocation, and Country entities
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit);

        return $this->render('data_acquisition/index.html.twig', [
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
            ->select('s.name', 's.latitude', 's.longitude') // select only necessary fields
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

        return $this->render('data_acquisition/station.html.twig', [
            'station' => $station,
            'controller_name' => 'DataAcquisitionController',
        ]);
    }

    #[Route('/dataacquisition/data', name: 'app_data_acquisition_data')]
    public function getAllData(Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        $limit = 10; // how many results per page

        $query = $this->entityManager
            ->getRepository(Weather::class)
            ->createQueryBuilder('w')
            ->orderBy('w.DATE', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit);

        return $this->render('data_acquisition/data.html.twig', [
            'weatherData' => $paginator,
            'current_page' => $currentPage,
            'total_pages' => ceil(count($paginator) / $limit),
            'controller_name' => 'DataAcquisitionController',
        ]);
    }

}