<?php

namespace App\Entity;

use App\Repository\TempCorrectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TempCorrectionRepository::class)]
class TempCorrection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $STN;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $DATE;

    #[ORM\Column(type: "time", nullable: true)]
    private ?\DateTimeInterface $TIME;

    #[ORM\Column(type:"float", nullable: true)]
    private ?float $CorrectedTEMP;

    #[ORM\Column(type:"float", nullable: true)]
    private ?float $OriginalTEMP;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSTN(): ?int
    {
        return $this->STN;
    }

    public function setSTN(?int $STN): self
    {
        $this->STN = $STN;
        return $this;
    }

    public function getDATE(): ?\DateTimeInterface
    {
        return $this->DATE;
    }

    public function setDATE(?\DateTimeInterface $DATE): self
    {
        $this->DATE = $DATE;
        return $this;
    }

    public function getTIME(): ?\DateTimeInterface
    {
        return $this->TIME;
    }

    public function setTIME(?\DateTimeInterface $TIME): self
    {
        $this->TIME = $TIME;
        return $this;
    }

    public function getCorrectedTEMP(): ?float
    {
        return $this->CorrectedTEMP;
    }

    public function setCorrectedTEMP(?float $CorrectedTEMP): self
    {
        $this->CorrectedTEMP = $CorrectedTEMP;
        return $this;
    }

    public function getOriginalTEMP(): ?float
    {
        return $this->OriginalTEMP;
    }

    public function setOriginalTEMP(?float $OriginalTEMP): self
    {
        $this->OriginalTEMP = $OriginalTEMP;
        return $this;
    }

}
