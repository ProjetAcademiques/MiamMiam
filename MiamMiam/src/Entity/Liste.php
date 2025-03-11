<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeRepository::class)]
class Liste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'listes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $creePar = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeCreation = null;

    public function __construct()
    {
        $this->DateDeCreation = new \DateTime();
        $this->creePar = ;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCreePar(): ?Utilisateur
    {
        return $this->creePar;
    }

    public function setCreePar(?Utilisateur $creePar): static
    {
        $this->creePar = $creePar;

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
}
