<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end;

    #[ORM\Column(length: 255)]
    private ?string $name_holder;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country_code;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CoordinatesType;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $elevation = null;

    #[ORM\Column]
    private ?bool $TEMP = false;

    #[ORM\Column]
    private ?bool $DEWP = false;

    #[ORM\Column]
    private ?bool $STP = false;

    #[ORM\Column]
    private ?bool $SLP = false;

    #[ORM\Column]
    private ?bool $VISIB = false;

    #[ORM\Column]
    private ?bool $WDSP = false;

    #[ORM\Column]
    private ?bool $PRCP = false;

    #[ORM\Column]
    private ?bool $SNDP = false;

    #[ORM\Column]
    private ?bool $FRSHTT = false;

    #[ORM\Column]
    private ?bool $CLDC = false;

    #[ORM\Column]
    private ?bool $WNDDIR = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getNameHolder(): ?string
    {
        return $this->name_holder;
    }

    public function setNameHolder(string $name_holder): static
    {
        $this->name_holder = $name_holder;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(?string $country_code): static
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCoordinatesType()
    {
        return $this->CoordinatesType;
    }

    public function setCoordinatesType(?string $CoordinatesType): static
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

    public function isTEMP(): ?bool
    {
        return $this->TEMP;
    }

    public function setTEMP(?bool $TEMP): static
    {
        $this->TEMP = $TEMP;

        return $this;
    }

    public function isDEWP(): ?bool
    {
        return $this->DEWP;
    }

    public function setDEWP(?bool $DEWP): static
    {
        $this->DEWP = $DEWP;

        return $this;
    }

    public function isSTP(): ?bool
    {
        return $this->STP;
    }

    public function setSTP(?bool $STP): static
    {
        $this->STP = $STP;

        return $this;
    }

    public function isSLP(): ?bool
    {
        return $this->SLP;
    }

    public function setSLP(?bool $SLP): static
    {
        $this->SLP = $SLP;

        return $this;
    }

    public function isVISIB(): ?bool
    {
        return $this->VISIB;
    }

    public function setVISIB(?bool $VISIB): static
    {
        $this->VISIB = $VISIB;

        return $this;
    }

    public function isWDSP(): ?bool
    {
        return $this->WDSP;
    }

    public function setWDSP(?bool $WDSP): static
    {
        $this->WDSP = $WDSP;

        return $this;
    }

    public function isPRCP(): ?bool
    {
        return $this->PRCP;
    }

    public function setPRCP(?bool $PRCP): static
    {
        $this->PRCP = $PRCP;

        return $this;
    }

    public function isSNDP(): ?bool
    {
        return $this->SNDP;
    }

    public function setSNDP(?bool $SNDP): static
    {
        $this->SNDP = $SNDP;

        return $this;
    }

    public function isFRSHTT(): ?bool
    {
        return $this->FRSHTT;
    }

    public function setFRSHTT(?bool $FRSHTT): static
    {
        $this->FRSHTT = $FRSHTT;

        return $this;
    }

    public function isCLDC(): ?bool
    {
        return $this->CLDC;
    }

    public function setCLDC(?bool $CLDC): static
    {
        $this->CLDC = $CLDC;

        return $this;
    }

    public function isWNDDIR(): ?bool
    {
        return $this->WNDDIR;
    }

    public function setWNDDIR(?bool $WNDDIR): static
    {
        $this->WNDDIR = $WNDDIR;

        return $this;
    }
}
