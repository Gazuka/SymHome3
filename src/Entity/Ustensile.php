<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UstensileRepository")
 */
class Ustensile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recette", mappedBy="ustensile")
     */
    private $recettes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EtapeRecette", mappedBy="ustensiles")
     */
    private $etapesRecette;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->etapesRecette = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Recette[]
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->addUstensile($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->contains($recette)) {
            $this->recettes->removeElement($recette);
            $recette->removeUstensile($this);
        }

        return $this;
    }

    /**
     * @return Collection|EtapeRecette[]
     */
    public function getEtapesRecette(): Collection
    {
        return $this->etapesRecette;
    }

    public function addEtapesRecette(EtapeRecette $etapesRecette): self
    {
        if (!$this->etapesRecette->contains($etapesRecette)) {
            $this->etapesRecette[] = $etapesRecette;
            $etapesRecette->addUstensile($this);
        }

        return $this;
    }

    public function removeEtapesRecette(EtapeRecette $etapesRecette): self
    {
        if ($this->etapesRecette->contains($etapesRecette)) {
            $this->etapesRecette->removeElement($etapesRecette);
            $etapesRecette->removeUstensile($this);
        }

        return $this;
    }
}
