<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteRepository")
 */
class Recette
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $portion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ustensile", inversedBy="recettes")
     */
    private $ustensile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="recette")
     */
    private $ingredients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EtapeRecette", mappedBy="recette")
     */
    private $etapesRecette;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Repas", mappedBy="recettes")
     */
    private $repas;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Preparation", mappedBy="recettes")
     */
    private $preparations;

    public function __construct()
    {
        $this->ustensile = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->etapesRecette = new ArrayCollection();
        $this->repas = new ArrayCollection();
        $this->preparations = new ArrayCollection();
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

    public function getPortion(): ?int
    {
        return $this->portion;
    }

    public function setPortion(?int $portion): self
    {
        $this->portion = $portion;

        return $this;
    }

    /**
     * @return Collection|Ustensile[]
     */
    public function getUstensile(): Collection
    {
        return $this->ustensile;
    }

    public function addUstensile(Ustensile $ustensile): self
    {
        if (!$this->ustensile->contains($ustensile)) {
            $this->ustensile[] = $ustensile;
        }

        return $this;
    }

    public function removeUstensile(Ustensile $ustensile): self
    {
        if ($this->ustensile->contains($ustensile)) {
            $this->ustensile->removeElement($ustensile);
        }

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecette($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecette() === $this) {
                $ingredient->setRecette(null);
            }
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
            $etapesRecette->setRecette($this);
        }

        return $this;
    }

    public function removeEtapesRecette(EtapeRecette $etapesRecette): self
    {
        if ($this->etapesRecette->contains($etapesRecette)) {
            $this->etapesRecette->removeElement($etapesRecette);
            // set the owning side to null (unless already changed)
            if ($etapesRecette->getRecette() === $this) {
                $etapesRecette->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Repas[]
     */
    public function getRepas(): Collection
    {
        return $this->repas;
    }

    public function addRepa(Repas $repa): self
    {
        if (!$this->repas->contains($repa)) {
            $this->repas[] = $repa;
            $repa->addRecette($this);
        }

        return $this;
    }

    public function removeRepa(Repas $repa): self
    {
        if ($this->repas->contains($repa)) {
            $this->repas->removeElement($repa);
            $repa->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection|Preparation[]
     */
    public function getPreparations(): Collection
    {
        return $this->preparations;
    }

    public function addPreparation(Preparation $preparation): self
    {
        if (!$this->preparations->contains($preparation)) {
            $this->preparations[] = $preparation;
            $preparation->addRecette($this);
        }

        return $this;
    }

    public function removePreparation(Preparation $preparation): self
    {
        if ($this->preparations->contains($preparation)) {
            $this->preparations->removeElement($preparation);
            $preparation->removeRecette($this);
        }

        return $this;
    }
}
