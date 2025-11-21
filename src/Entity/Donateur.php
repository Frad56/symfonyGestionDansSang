<?php

namespace App\Entity;

use App\Repository\DonateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: DonateurRepository::class)]
class Donateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_DONATEUR'];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 200)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $groupeSanguin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $derniereDateDon = null;

    /**
     * @var Collection<int, Don>
     */
    #[ORM\OneToMany(targetEntity: 'App\Entity\Don', mappedBy: 'donateurId')]
    private Collection $dons;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: 'App\Entity\RendezVous', mappedBy: 'donateur')]
    private Collection $rendezVous;

    public function __construct()
    {
        $this->dons = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
    }

    // ==== UserInterface & PasswordAuthenticatedUserInterface ====

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    // ==== Donateur specific fields ====

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
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

    public function getDerniereDateDon(): ?\DateTime
    {
        return $this->derniereDateDon;
    }

    public function setDerniereDateDon(?\DateTime $derniereDateDon): static
    {
        $this->derniereDateDon = $derniereDateDon;
        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(\App\Entity\Don $don): static
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setDonateurId($this);
        }
        return $this;
    }

    public function removeDon(\App\Entity\Don $don): static
    {
        if ($this->dons->removeElement($don)) {
            if ($don->getDonateurId() === $this) {
                $don->setDonateurId(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouse(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVouse(\App\Entity\RendezVous $rendezVouse): static
    {
        if (!$this->rendezVous->contains($rendezVouse)) {
            $this->rendezVous->add($rendezVouse);
            $rendezVouse->setDonateur($this);
        }
        return $this;
    }

    public function removeRendezVouse(\App\Entity\RendezVous $rendezVouse): static
    {
        if ($this->rendezVous->removeElement($rendezVouse)) {
            if ($rendezVouse->getDonateur() === $this) {
                $rendezVouse->setDonateur(null);
            }
        }
        return $this;
    }
}