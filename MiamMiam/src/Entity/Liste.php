<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    /**
     * @var Collection<int, ListeArticle>
     */
    #[ORM\OneToMany(targetEntity: ListeArticle::class, mappedBy: 'liste', orphanRemoval: true)]
    private Collection $liste_article;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'listes')]
    private Collection $users;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $periode = null;

    public function __construct()
    {
        $this->liste_article = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    /**
     * @return Collection<int, ListeArticle>
     */
    public function getListeArticle(): Collection
    {
        return $this->liste_article;
    }

    public function addIdListeArticle(ListeArticle $idListeArticle): static
    {
        if (!$this->liste_article->contains($idListeArticle)) {
            $this->liste_article->add($idListeArticle);
            $idListeArticle->setListe($this);
        }

        return $this;
    }

    public function removeIdListeArticle(ListeArticle $idListeArticle): static
    {
        if ($this->liste_article->removeElement($idListeArticle)) {
            // set the owning side to null (unless already changed)
            if ($idListeArticle->getListe() === $this) {
                $idListeArticle->setListe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addListe($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeListe($this);
        }

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(?string $periode): static
    {
        $this->periode = $periode;

        return $this;
    }
}
