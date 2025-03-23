<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * @var Collection<int, ListeArticle>
     */
    #[ORM\OneToMany(targetEntity: ListeArticle::class, mappedBy: 'id_article', orphanRemoval: true)]
    private Collection $liste_article;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\ManyToMany(targetEntity: Type::class, mappedBy: 'article')]
    private Collection $type;

    /**
     * @var Collection<int, Magasin>
     */
    #[ORM\ManyToMany(targetEntity: Magasin::class, inversedBy: 'articles')]
    private Collection $magasin;

    public function __construct()
    {
        $this->liste_article = new ArrayCollection();
        $this->type = new ArrayCollection();
        $this->magasin = new ArrayCollection();
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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
            $idListeArticle->setArticle($this);
        }

        return $this;
    }

    public function removeIdListeArticle(ListeArticle $idListeArticle): static
    {
        if ($this->liste_article->removeElement($idListeArticle)) {
            // set the owning side to null (unless already changed)
            if ($idListeArticle->getArticle() === $this) {
                $idListeArticle->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Type $type): static
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
            $type->addArticle($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->type->removeElement($type)) {
            $type->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getMagasin(): Collection
    {
        return $this->magasin;
    }

    public function addMagasin(Magasin $magasin): static
    {
        if (!$this->magasin->contains($magasin)) {
            $this->magasin->add($magasin);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): static
    {
        $this->magasin->removeElement($magasin);

        return $this;
    }
}
