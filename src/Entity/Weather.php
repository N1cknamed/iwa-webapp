<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\WeatherRepository;

#[ORM\Entity(repositoryClass: WeatherRepository::class)]

class Weather
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $STN;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $date;

    #[ORM\Column(type: "time", nullable: true)]
    private ?\DateTimeInterface $time;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $TEMP;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $DEWP;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $STP;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $SLP;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $VISIB;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $WDSP;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $PRCP;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $SNDP;

    #[ORM\Column(type: "string", length: 6, nullable: true)]
    private ?string $FRSHTT;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $CLDC;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $WNDDIR;


    public function getSTN(): ?int
    {
        return $this->STN;
    }

    public function setSTN(?int $STN): self
    {
        $this->STN = $STN;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function getTEMP(): ?float
    {
        return $this->TEMP;
    }

    public function setTEMP(?float $TEMP): self
    {
        $this->TEMP = $TEMP;
        return $this;
    }

    public function getDEWP(): ?float
    {
        return $this->DEWP;
    }

    public function setDEWP(?float $DEWP): self
    {
        $this->DEWP = $DEWP;
        return $this;
    }

    public function getSTP(): ?float
    {
        return $this->STP;
    }

    public function setSTP(?float $STP): self
    {
        $this->STP = $STP;
        return $this;
    }

    public function getSLP(): ?float
    {
        return $this->SLP;
    }

    public function setSLP(?float $SLP): self
    {
        $this->SLP = $SLP;
        return $this;
    }

    public function getVISIB(): ?float
    {
        return $this->VISIB;
    }

    public function setVISIB(?float $VISIB): self
    {
        $this->VISIB = $VISIB;
        return $this;
    }

    public function getWDSP(): ?float
    {
        return $this->WDSP;
    }

    public function setWDSP(?float $WDSP): self
    {
        $this->WDSP = $WDSP;
        return $this;
    }

    public function getPRCP(): ?float
    {
        return $this->PRCP;
    }

    public function setPRCP(?float $PRCP): self
    {
        $this->PRCP = $PRCP;
        return $this;
    }

    public function getSNDP(): ?float
    {
        return $this->SNDP;
    }

    public function setSNDP(?float $SNDP): self
    {
        $this->SNDP = $SNDP;
        return $this;
    }

    public function getFRSHTT(): ?string
    {
        return $this->FRSHTT;
    }

    public function setFRSHTT(?string $FRSHTT): self
    {
        $this->FRSHTT = $FRSHTT;
        return $this;
    }

    public function getCLDC(): ?float
    {
        return $this->CLDC;
    }

    public function setCLDC(?float $CLDC): self
    {
        $this->CLDC = $CLDC;
        return $this;
    }

    public function getWNDDIR(): ?int
    {
        return $this->WNDDIR;
    }

    public function setWNDDIR(?int $WNDDIR): self
    {
        $this->WNDDIR = $WNDDIR;
        return $this;
    }
}
