<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\ManyToMany(targetEntity: Type::class, mappedBy: 'articles')]
    private Collection $types;

    /**
     * @var Collection<int, Magasin>
     */
    #[ORM\ManyToMany(targetEntity: Magasin::class, mappedBy: 'articles')]
    private Collection $magasins;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column]
    private ?int $quantitee = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateAjout = null;

    /**
     * @var Collection<int, Contenue>
     */
    #[ORM\ManyToMany(targetEntity: Contenue::class, mappedBy: 'id_article')]
    private Collection $contenues;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->magasins = new ArrayCollection();
        $this->contenues = new ArrayCollection();
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

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->addArticle($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->types->removeElement($type)) {
            $type->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getMagasins(): Collection
    {
        return $this->magasins;
    }

    public function addMagasin(Magasin $magasin): static
    {
        if (!$this->magasins->contains($magasin)) {
            $this->magasins->add($magasin);
            $magasin->addArticle($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): static
    {
        if ($this->magasins->removeElement($magasin)) {
            $magasin->removeArticle($this);
        }

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantitee(): ?int
    {
        return $this->quantitee;
    }

    public function setQuantitee(int $quantitee): static
    {
        $this->quantitee = $quantitee;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->DateAjout;
    }

    public function setDateAjout(\DateTimeInterface $DateAjout): static
    {
        $this->DateAjout = $DateAjout;

        return $this;
    }

    /**
     * @return Collection<int, Contenue>
     */
    public function getContenues(): Collection
    {
        return $this->contenues;
    }

    public function addContenue(Contenue $contenue): static
    {
        if (!$this->contenues->contains($contenue)) {
            $this->contenues->add($contenue);
            $contenue->addIdArticle($this);
        }

        return $this;
    }

    public function removeContenue(Contenue $contenue): static
    {
        if ($this->contenues->removeElement($contenue)) {
            $contenue->removeIdArticle($this);
        }

        return $this;
    }
}
