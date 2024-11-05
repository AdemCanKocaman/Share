<?php

namespace App\Entity;

use App\Repository\ScategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use App\EventListener\ScategorieListener;

#[ORM\Entity(repositoryClass: ScategorieRepository::class)]
#[ORM\EntityListeners([ScategorieListener::class])]
class Scategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\ManyToOne(inversedBy: 'scategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categorie = null;

    #[ORM\Column]
    private ?int $numero = null;

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

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;
        return $this;
    }
}
