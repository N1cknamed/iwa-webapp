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

    #[ORM\OneToOne(targetEntity: ContractCoordinates::class)]
    #[ORM\JoinColumn(name: 'ContractCoordinates', referencedColumnName: 'id', nullable: true)]
    private ?ContractCoordinates $coordinates;

    #[ORM\OneToOne(targetEntity: ContractData::class)]
    #[ORM\JoinColumn(name: 'ContractData', referencedColumnName: 'id', nullable: true)]
    private ?ContractData $data;

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

    public function getCoordinates(): ?ContractCoordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(?ContractCoordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getData(): ?ContractData
    {
        return $this->data;
    }

    public function setData(?ContractData $data): self
    {
        $this->data = $data;

        return $this;
    }
}
