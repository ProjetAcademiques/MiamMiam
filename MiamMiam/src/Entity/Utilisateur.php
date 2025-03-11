<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $Adressemail = null;

    #[ORM\Column(length: 255)]
    private ?string $MDP = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeCreation = null;

    /**
     * @var Collection<int, Liste>
     */
    #[ORM\OneToMany(targetEntity: Liste::class, mappedBy: 'creePar')]
    private Collection $listes;

    
    public function __construct()
    {
        $this->listes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getAdressemail(): ?string
    {
        return $this->Adressemail;
    }

    public function setAdressemail(string $Adressemail): static
    {
        $this->Adressemail = $Adressemail;

        return $this;
    }

    public function getMDP(): ?string
    {
        return $this->MDP;
    }

    public function setMDP(string $MDP): static
    {
        $this->MDP = $MDP;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->DateDeCreation;
    }

    public function setDateDeCreation(\DateTimeInterface $DateDeCreation): static
    {
        $this->DateDeCreation = $DateDeCreation;

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getListes(): Collection
    {
        return $this->listes;
    }

    public function addListe(Liste $liste): static
    {
        if (!$this->listes->contains($liste)) {
            $this->listes->add($liste);
            $liste->setCreePar($this);
        }

        return $this;
    }

    public function removeListe(Liste $liste): static
    {
        if ($this->listes->removeElement($liste)) {
            // set the owning side to null (unless already changed)
            if ($liste->getCreePar() === $this) {
                $liste->setCreePar(null);
            }
        }

        return $this;
    }
}
