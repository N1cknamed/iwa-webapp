<?php

namespace App\Entity;

use App\Repository\MalfunctionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MalfunctionRepository::class)]
class Malfunction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(name: "station_name", referencedColumnName: "name", nullable: false)]
    private Station $station;

    #[ORM\Column(type: "string", length: 255)]
    private string $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE", type="datetime", nullable=true)
     */
    private $DATE;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStation(): Station
    {
        return $this->station;
    }

    public function setStation(Station $station): self
    {
        $this->station = $station;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDATE(): ?\DateTime
    {
        return $this->DATE;
    }

    public function setDATE(?\DateTime $DATE): self
    {
        $this->DATE = $DATE;
        return $this;
    }
}