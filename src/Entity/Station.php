<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: StationRepository::class)]
#[ORM\Table(name: "station")]
class Station
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 10)]
    private string $name;

    #[ORM\Column(type: "float")]
    private float $longitude;

    #[ORM\Column(type: "float")]
    private float $latitude;

    #[ORM\Column(type: "float")]
    private float $elevation;

    #[ORM\OneToOne(targetEntity: Geolocation::class, mappedBy: "station")]
    private ?Geolocation $geolocation = null;

    #[ORM\OneToMany(targetEntity: NearestLocation::class, mappedBy: "station")]
    private Collection $nearestLocations;

    #[ORM\OneToMany(targetEntity: Weather::class, mappedBy: "station")]
    private Collection $weather;

    public function __construct()
    {
        $this->nearestLocations = new ArrayCollection();
        $this->weather = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getElevation(): float
    {
        return $this->elevation;
    }

    public function setElevation(float $elevation): self
    {
        $this->elevation = $elevation;

        return $this;
    }

    public function getGeolocation(): ?Geolocation
    {
        return $this->geolocation;
    }

    public function setGeolocation(?Geolocation $geolocation): self
    {
        // unset the owning side of the relation if necessary
        if ($geolocation === null && $this->geolocation !== null) {
            $this->geolocation->setStation(null);
        }

        // set the owning side of the relation if necessary
        if ($geolocation !== null && $geolocation->getStation() !== $this) {
            $geolocation->setStation($this);
        }

        $this->geolocation = $geolocation;

        return $this;
    }

    /**
     * @return Collection|NearestLocation[]
     */
    public function getNearestLocations(): Collection
    {
        return $this->nearestLocations;
    }

    public function addNearestLocation(NearestLocation $nearestLocation): self
    {
        if (!$this->nearestLocations->contains($nearestLocation)) {
            $this->nearestLocations[] = $nearestLocation;
            $nearestLocation->setStation($this);
        }

        return $this;
    }

    public function removeNearestLocation(NearestLocation $nearestLocation): self
    {
        if ($this->nearestLocations->removeElement($nearestLocation)) {
            // set the owning side to null (unless already changed)
            if ($nearestLocation->getStation() === $this) {
                $nearestLocation->setStation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Weather[]
     */
    public function getWeather(): Collection
    {
        return $this->weather;
    }

    public function addWeather(Weather $weather): self
    {
        if (!$this->weather->contains($weather)) {
            $this->weather[] = $weather;
            $weather->setStation($this);
        }

        return $this;
    }

    public function removeWeather(Weather $weather): self
    {
        if ($this->weather->removeElement($weather)) {
            // set the owning side to null (unless already changed)
            if ($weather->getStation() === $this) {
                $weather->setStation(null);
            }
        }

        return $this;
    }
}