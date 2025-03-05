<?php

namespace App\Entity;

use App\Repository\ContenueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContenueRepository::class)]
class Contenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?liste $id_liste = null;

    /**
     * @var Collection<int, articles>
     */
    #[ORM\ManyToMany(targetEntity: articles::class, inversedBy: 'contenues')]
    private Collection $id_article;

    public function __construct()
    {
        $this->id_article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdListe(): ?liste
    {
        return $this->id_liste;
    }

    public function setIdListe(?liste $id_liste): static
    {
        $this->id_liste = $id_liste;

        return $this;
    }

    /**
     * @return Collection<int, articles>
     */
    public function getIdArticle(): Collection
    {
        return $this->id_article;
    }

    public function addIdArticle(articles $idArticle): static
    {
        if (!$this->id_article->contains($idArticle)) {
            $this->id_article->add($idArticle);
        }

        return $this;
    }

    public function removeIdArticle(articles $idArticle): static
    {
        $this->id_article->removeElement($idArticle);

        return $this;
    }
}
