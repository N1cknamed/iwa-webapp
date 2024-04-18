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

        $query = $this->entityManager
            ->getRepository(Weather::class)
            ->createQueryBuilder('w')
            ->orderBy('w.DATE', 'DESC');
//            ->getQuery();

//        $paginator = new Paginator($query);
//        $paginator->getQuery()
//            ->setFirstResult($limit * ($currentPage - 1))
//            ->setMaxResults($limit);

        // Handle filtering
        $STN = $request->query->get('STN');
        $DATE = $request->query->get('DATE');
        $TIME = $request->query->get('TIME');
        $TEMP = $request->query->get('TEMP');
        $DEWP = $request->query->get('DEWP');
        $STP = $request->query->get('STP');
        $SLP = $request->query->get('SLP');
        $VISIB = $request->query->get('VISIB');
        $WDSP = $request->query->get('WDSP');
        $PRCP = $request->query->get('PRCP');
        $SNDP = $request->query->get('SNDP');
        $FRSHTTtt = $request->query->get('FRSHTTtt');
        $CLDC = $request->query->get('CLDC');
        $WNDDIR = $request->query->get('winddir');
        $limit = $request->query->get('limit');
        $timeStart = $request->query->get('startTime');
        $timeEnd = $request->query->get('endTime');
        $dateStart = $request->query->get('startDate');
        $dateEnd = $request->query->get('endDate');

        // Create a query builder instance
        $queryBuilder = $this->entityManager
            ->getRepository(Weather::class)
            ->createQueryBuilder('w');
//        if ($limit) {
//            $queryBuilder->setMaxResults($limit);
//        }else{
//            $limit = 10;
//            $queryBuilder->setMaxResults(10);
//
//        }

        // Apply filters
        if ($STN) {
            $queryBuilder->andWhere('w.STN = :STN')
                ->setParameter('STN', $STN);
        }
//        if ($DATE) {
//            $queryBuilder->andWhere('w.DATE = :DATE')
//                ->setParameter('DATE', new \DateTime($DATE));
//        }
        if ($dateStart && $dateEnd) {
            $queryBuilder->andWhere('w.DATE BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', new \DateTime($dateStart))
                ->setParameter('endDate', new \DateTime($dateEnd));
        } elseif ($dateStart) {
            $queryBuilder->andWhere('w.DATE >= :startDate')
                ->setParameter('startDate', new \DateTime($dateStart));
        } elseif ($dateEnd) {
            $queryBuilder->andWhere('w.DATE <= :endDate')
                ->setParameter('endDate', new \DateTime($dateEnd));
        }
        if ($timeStart && $timeEnd) {
            $queryBuilder->andWhere('w.TIME BETWEEN :startTime AND :endTime')
                ->setParameter('startTime', \DateTime::createFromFormat('H:i:s', $timeStart))
                ->setParameter('endTime', \DateTime::createFromFormat('H:i:s', $timeEnd));
        } elseif ($timeStart) {
            $queryBuilder->andWhere('w.TIME >= :startTime')
                ->setParameter('startTime', \DateTime::createFromFormat('H:i:s', $timeStart));
        } elseif ($timeEnd) {
            $queryBuilder->andWhere('w.TIME <= :endTime')
                ->setParameter('endTime', \DateTime::createFromFormat('H:i:s', $timeEnd));
        }
        if ($TEMP) {
            $queryBuilder->andWhere('w.TEMP = :TEMP')
                ->setParameter('TEMP', $TEMP);
        }
        if ($DEWP) {
            $queryBuilder->andWhere('w.DEWP = :DEWP')
                ->setParameter('DEWP', $DEWP);
        }
        if ($STP) {
            $queryBuilder->andWhere('w.STP = :STP')
                ->setParameter('STP', $STP);
        }
        if ($SLP) {
            $queryBuilder->andWhere('w.SLP = :SLP')
                ->setParameter('SLP', $SLP);
        }
        if ($VISIB) {
            $queryBuilder->andWhere('w.VISIB = :VISIB')
                ->setParameter('VISIB', $VISIB);
        }
        if ($WDSP) {
            $queryBuilder->andWhere('w.WDSP = :WDSP')
                ->setParameter('WDSP', $WDSP);
        }
        if ($PRCP) {
            $queryBuilder->andWhere('w.PRCP = :PRCP')
                ->setParameter('PRCP', $PRCP);
        }
        if ($SNDP) {
            $queryBuilder->andWhere('w.SNDP = :SNDP')
                ->setParameter('SNDP', $SNDP);
        }
        if ($FRSHTTtt) {
            $queryBuilder->andWhere('w.FRSHTT = :FRSHTTtt')
                ->setParameter('FRSHTTtt', $FRSHTTtt);
        }
        if ($CLDC) {
            $queryBuilder->andWhere('w.CLDC = :CLDC')
                ->setParameter('CLDC', $CLDC);
        }
        if ($WNDDIR) {
            $queryBuilder->andWhere('w.WNDDIR = :WNDDIR')
                ->setParameter('WNDDIR', $WNDDIR);
        }

//        // Execute the filtered query
//        $filteredQuery = $queryBuilder->getQuery();
//        $filteredWeatherData = $filteredQuery->getResult();
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
            // Pass the filters to the view
            'filters' => [
                'STN' => $STN,
                'DATE' => $DATE,
                'TIME' => $TIME,
                'TEMP' => $TEMP,
                'DEWP' => $DEWP,
                'STP' => $STP,
                'SLP' => $SLP,
                'VISIB' => $VISIB,
                'WDSP' => $WDSP,
                'PRCP' => $PRCP,
                'SNDP' => $SNDP,
                'FRSHTTtt' => $FRSHTTtt,
                'CLDC' => $CLDC,
                'WNDDIR' => $WNDDIR,
                'limit' => $limit,
                'startTime' => $timeStart,
                'endTime' => $timeEnd,
                'startDate' => $dateStart,
                'endDate' => $dateEnd,
                // Add other filters here...
            ],
        ]);
    }
}