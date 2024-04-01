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
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_holder = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $afloopdatum = null;

    #[ORM\Column(length: 255)]
    private ?string $station = null;

    #[ORM\Column]
    private array $data = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getAfloopdatum(): ?\DateTimeInterface
    {
        return $this->afloopdatum;
    }

    public function setAfloopdatum(\DateTimeInterface $afloopdatum): static
    {
        $this->afloopdatum = $afloopdatum;

        return $this;
    }

    public function getStation(): ?string
    {
        return $this->station;
    }

    public function setStation(string $station): static
    {
        $this->station = $station;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
