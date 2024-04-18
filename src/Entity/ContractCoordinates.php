<?php

namespace App\Entity;

use App\Entity\Contract;
use App\Repository\ContractCoordinatesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractCoordinatesRepository::class)]
class ContractCoordinates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\OneToOne(targetEntity: contract::class)]
    private ?int $id = null;

    #[ORM\Column(type: 'coordinates_enum')]
    private $CoordinatesType;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $elevation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCoordinatesType()
    {
        return $this->CoordinatesType;
    }

    public function setCoordinatesType($CoordinatesType): static
    {
        $this->CoordinatesType = $CoordinatesType;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getElevation(): ?float
    {
        return $this->elevation;
    }

    public function setElevation(?float $elevation): static
    {
        $this->elevation = $elevation;

        return $this;
    }
}
