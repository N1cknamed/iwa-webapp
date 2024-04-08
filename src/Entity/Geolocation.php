<?php

namespace App\Entity;

use App\Repository\GeolocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GeolocationRepository::class)]
class Geolocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 10)]
    private ?string $station_name = null;

    #[ORM\Column(type: "string", length: 2)]
    private ?string $country_code = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $island = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $county = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $hamlet = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $town = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $municipality = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $state_district = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $administrative = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $village = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $province = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $locality = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $postcode = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $country = null;

    #[ORM\OneToOne(targetEntity: Station::class, inversedBy: "geolocation")]
    #[ORM\JoinColumn(name: "station_name", referencedColumnName: "name")]
    private ?Station $station = null;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[ORM\JoinColumn(name: "country_code", referencedColumnName: "country_code")]
    private ?Country $countryEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStationName(): ?string
    {
        return $this->station_name;
    }

    public function setStationName(string $station_name): self
    {
        $this->station_name = $station_name;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getIsland(): ?string
    {
        return $this->island;
    }

    public function setIsland(?string $island): self
    {
        $this->island = $island;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(?string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getHamlet(): ?string
    {
        return $this->hamlet;
    }

    public function setHamlet(?string $hamlet): self
    {
        $this->hamlet = $hamlet;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getMunicipality(): ?string
    {
        return $this->municipality;
    }

    public function setMunicipality(?string $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getStateDistrict(): ?string
    {
        return $this->state_district;
    }

    public function setStateDistrict(?string $state_district): self
    {
        $this->state_district = $state_district;

        return $this;
    }

    public function getAdministrative(): ?string
    {
        return $this->administrative;
    }

    public function setAdministrative(?string $administrative): self
    {
        $this->administrative = $administrative;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getVillage(): ?string
    {
        return $this->village;
    }

    public function setVillage(?string $village): self
    {
        $this->village = $village;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(?string $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

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

    public function getCountryEntity(): ?Country
    {
        return $this->countryEntity;
    }

    public function setCountryEntity(?Country $countryEntity): self
    {
        $this->countryEntity = $countryEntity;

        return $this;
    }
}