<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomLieu = null;

    #[ORM\Column(length:255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column]
    private ?int $codePostal = null;

    /**
     * @var Collection<int, Collecte>
     */
    #[ORM\OneToMany(targetEntity: Collecte::class, mappedBy: 'lieu', orphanRemoval: true)]
    private Collection $collecte;

    public function __construct()
    {
        $this->collecte = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLieu(): ?string
    {
        return $this->nomLieu;
    }

    public function setNomLieu(string $nomLieu): static
    {
        $this->nomLieu = $nomLieu;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Collecte>
     */
    public function getCollecte(): Collection
    {
        return $this->collecte;
    }

    public function addCollecte(Collecte $collecte): static
    {
        if (!$this->collecte->contains($collecte)) {
            $this->collecte->add($collecte);
            $collecte->setLieu($this);
        }

        return $this;
    }

    public function removeCollecte(Collecte $collecte): static
    {
        if ($this->collecte->removeElement($collecte)) {
            // set the owning side to null (unless already changed)
            if ($collecte->getLieu() === $this) {
                $collecte->setLieu(null);
            }
        }

        return $this;
    }
}
