<?php

namespace App\Repository;

use App\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weather::class);
    }

    public function findWind()
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT w.id, w.STN, g.country, g.city, s.longitude, s.latitude, s.elevation, w.DATE, w.TIME, w.WDSP, w.WNDDIR
            FROM weather w
            JOIN geolocation g ON w.STN = g.station_name
            JOIN station s ON w.STN = s.name
            WHERE g.country_code = \'JP\'
            ORDER BY w.DATE DESC, w.TIME DESC
            ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }

    public function findRain()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT w.id, w.STN, g.country, g.city, s.longitude, s.latitude, s.elevation, w.DATE, w.TIME, w.PRCP
            FROM weather w
            JOIN geolocation g ON w.STN = g.station_name
            JOIN station s ON w.STN = s.name
            WHERE (g.country_code IN (SELECT r.country_code FROM region r) OR g.country_code = \'JP\') AND w.FRSHTT LIKE \'_1____\' AND w.TEMP < 13.9
            ORDER BY w.DATE DESC, w.TIME DESC
            ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative(); 
    }
}
