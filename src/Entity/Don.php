<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $datedon = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(length: 255)]
    private ?string $typeDon = null;

    #[ORM\Column]
    private ?bool $apte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Donateur $donateurId = null;

    #[ORM\ManyToOne(targetEntity: RendezVous::class)]
    #[ORM\JoinColumn(name: "rendez_vous_id", referencedColumnName: "id", nullable: false)]
    private ?RendezVous $rendezVous = null;
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedon(): ?\DateTime
    {
        return $this->datedon;
    }

    public function setDatedon(\DateTime $datedon): static
    {
        $this->datedon = $datedon;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getTypeDon(): ?string
    {
        return $this->typeDon;
    }

    public function setTypeDon(string $typeDon): static
    {
        $this->typeDon = $typeDon;

        return $this;
    }

    public function isApte(): ?bool
    {
        return $this->apte;
    }

    public function setApte(bool $apte): static
    {
        $this->apte = $apte;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDonateurId(): ?Donateur
    {
        return $this->donateurId;
    }

    public function setDonateurId(?Donateur $donateurId): static
    {
        $this->donateurId = $donateurId;

        return $this;
    }

    public function getRendezVous(): ?RendezVous
    {
        return $this->rendezVous;
    }

    public function setRendezVous(RendezVous $rendezVous): static
    {
        $this->rendezVous = $rendezVous;

        return $this;
    }

   
}
