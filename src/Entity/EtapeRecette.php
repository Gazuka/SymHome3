<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtapeRecetteRepository")
 */
class EtapeRecette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptif;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ingredient", inversedBy="etapesRecette")
     */
    private $ingredients;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ustensile", inversedBy="etapesRecette")
     */
    private $ustensiles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recette", inversedBy="etapesRecette")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recette;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->ustensiles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->descriptif;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function getDescriptifDynamique($portion = null): ?string
    {
        $quantiteOrigine = 100;//temporaire
        $portionOrigine = $this->recette->getPortion();
        if($portion == null){$portion = $portionOrigine;}
        $descriptif = $this->descriptif;
        $stop = false;
        do
        {
            if(preg_match('~\[\#(\w*)_(\d*)\#\]~', $descriptif, $match))
            {
                $quantite = ($quantiteOrigine / $portionOrigine * $portion) * $match[2] / 100;
                $descriptif = str_replace($match[0], $quantite."TO".$match[1], $descriptif);
                //$etape->setDescriptif($descriptif);              
            }
            else
            {
                $stop = true;
            }
        }
        while($stop==false);
        return $descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
        }

        return $this;
    }

    /**
     * @return Collection|Ustensile[]
     */
    public function getUstensiles(): Collection
    {
        return $this->ustensiles;
    }

    public function addUstensile(Ustensile $ustensile): self
    {
        if (!$this->ustensiles->contains($ustensile)) {
            $this->ustensiles[] = $ustensile;
        }

        return $this;
    }

    public function removeUstensile(Ustensile $ustensile): self
    {
        if ($this->ustensiles->contains($ustensile)) {
            $this->ustensiles->removeElement($ustensile);
        }

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
}
