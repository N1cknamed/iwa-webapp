<?php

namespace App\Entity;

use App\Repository\ContractDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractDataRepository::class)]
class ContractData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
