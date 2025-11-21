<?php
 
namespace App\Entity;
 
use App\Repository\StockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
 
#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 
    #[ORM\Column(length: 40)]
    private ?string $groupeSanguin = null;
 
   #[ORM\Column(type: Types::INTEGER)]
    private ?int $niveauActuel = null;
 
    #[ORM\Column(length: 30)]
    private ?string $niveauAlerte = null;
 
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dernierMiseAJour = null;
 
    public function getId(): ?int
    {
        return $this->id;
    }
 
    public function getGroupeSanguin(): ?string
    {
        return $this->groupeSanguin;
    }
 
    public function setGroupeSanguin(string $groupeSanguin): static
    {
        $this->groupeSanguin = $groupeSanguin;
 
        return $this;
    }
 
    public function getNiveauActuel(): ?int
    {
        return $this->niveauActuel;
    }
 
    public function setNiveauActuel(int $niveauActuel): static
    {
        $this->niveauActuel = $niveauActuel;
 
        return $this;
    }
 
    public function getNiveauAlerte(): ?string
    {
        return $this->niveauAlerte;
    }
 
    public function setNiveauAlerte(string $niveauAlerte): static
    {
        $this->niveauAlerte = $niveauAlerte;
 
        return $this;
    }
 
    public function getDernierMiseAJour(): ?\DateTime
    {
        return $this->dernierMiseAJour;
    }
 
    public function setDernierMiseAJour(?\DateTime $dernierMiseAJour): static
    {
        $this->dernierMiseAJour = $dernierMiseAJour;
 
        return $this;
    }
}
 
 