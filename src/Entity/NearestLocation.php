<?php

namespace App\Entity;

use App\Repository\NearestLocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NearestLocationRepository::class)]
#[ORM\Table(name: "nearestlocation")]
class NearestLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 10)]
    private string $station_name;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $administrative_region1 = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $administrative_region2 = null;

    #[ORM\Column(type: "string", length: 2)]
    private string $country_code;

    #[ORM\Column(type: "float")]
    private float $longitude;

    #[ORM\Column(type: "float")]
    private float $latitude;

    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(name: "station_name", referencedColumnName: "name")]
    private ?Station $station = null;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: "country_code", referencedColumnName: "country_code")]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStationName(): string
    {
        return $this->station_name;
    }

    public function setStationName(string $station_name): self
    {
        $this->station_name = $station_name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdministrativeRegion1(): ?string
    {
        return $this->administrative_region1;
    }

    public function setAdministrativeRegion1(?string $administrative_region1): self
    {
        $this->administrative_region1 = $administrative_region1;

        return $this;
    }

    public function getAdministrativeRegion2(): ?string
    {
        return $this->administrative_region2;
    }

    public function setAdministrativeRegion2(?string $administrative_region2): self
    {
        $this->administrative_region2 = $administrative_region2;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}