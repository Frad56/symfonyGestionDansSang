<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateHeureDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateheureFin = null;

    #[ORM\Column(length: 30)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    private ?Donateur $donateur = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    private ?Collecte $collecte = null;


   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeureDebut(): ?\DateTime
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTime $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateheureFin(): ?\DateTime
    {
        return $this->dateheureFin;
    }

    public function setDateheureFin(\DateTime $dateheureFin): static
    {
        $this->dateheureFin = $dateheureFin;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDonateur(): ?Donateur
    {
        return $this->donateur;
    }

    public function setDonateur(?Donateur $donateur): static
    {
        $this->donateur = $donateur;

        return $this;
    }

    public function getCollecte(): ?Collecte
    {
        return $this->collecte;
    }

    public function setCollecte(?Collecte $collecte): static
    {
        $this->collecte = $collecte;

        return $this;
    }

   

}
