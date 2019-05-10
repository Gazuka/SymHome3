<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Aliment", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $aliment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recette", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recette;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EtapeRecette", mappedBy="ingredients")
     */
    private $etapesRecette;

    public function __construct()
    {
        $this->etapesRecette = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getAliment(): ?Aliment
    {
        return $this->aliment;
    }

    public function setAliment(?Aliment $aliment): self
    {
        $this->aliment = $aliment;

        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): self
    {
        $this->recette = $recette;

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
            $etapesRecette->addIngredient($this);
        }

        return $this;
    }

    public function removeEtapesRecette(EtapeRecette $etapesRecette): self
    {
        if ($this->etapesRecette->contains($etapesRecette)) {
            $this->etapesRecette->removeElement($etapesRecette);
            $etapesRecette->removeIngredient($this);
        }

        return $this;
    }
}
