<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $titre = null;

    #[ORM\Column(length: 100)]
    private ?string $sujet = null;

    #[ORM\Column(length: 30)]
    private ?string $autor = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Scategorie>
     */
    #[ORM\OneToMany(targetEntity: Scategorie::class, mappedBy: 'categorie', orphanRemoval: true)]
    private Collection $scategories;

    public function __construct()
    {
        $this->scategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;
        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): static
    {
        $this->autor = $autor;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Scategorie>
     */
    public function getScategories(): Collection
    {
        return $this->scategories;
    }

    public function addScategory(Scategorie $scategory): static
    {
        if (!$this->scategories->contains($scategory)) {
            $this->scategories->add($scategory);
            $scategory->setCategorie($this);
        }

        return $this;
    }

    public function removeScategory(Scategorie $scategory): static
    {
        if ($this->scategories->removeElement($scategory)) {
            // set the owning side to null (unless already changed)
            if ($scategory->getCategorie() === $this) {
                $scategory->setCategorie(null);
            }
        }

        return $this;
    }
}
