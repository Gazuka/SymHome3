<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreparationRepository")
 */
class Preparation
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $datePreparation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recette", inversedBy="preparations")
     */
    private $recettes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Repas", inversedBy="preparations")
     */
    private $repas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Boite", mappedBy="preparation")
     */
    private $boites;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDisparition;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->boites = new ArrayCollection();
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

    public function getDatePreparation(): ?\DateTimeInterface
    {
        return $this->datePreparation;
    }

    public function setDatePreparation(?\DateTimeInterface $datePreparation): self
    {
        $this->datePreparation = $datePreparation;

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
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->contains($recette)) {
            $this->recettes->removeElement($recette);
        }

        return $this;
    }

    public function getRepas(): ?Repas
    {
        return $this->repas;
    }

    public function setRepas(?Repas $repas): self
    {
        $this->repas = $repas;

        return $this;
    }

    /**
     * @return Collection|Boite[]
     */
    public function getBoites(): Collection
    {
        return $this->boites;
    }

    public function addBoite(Boite $boite): self
    {
        if (!$this->boites->contains($boite)) {
            $this->boites[] = $boite;
            $boite->setPreparation($this);
        }

        return $this;
    }

    public function removeBoite(Boite $boite): self
    {
        if ($this->boites->contains($boite)) {
            $this->boites->removeElement($boite);
            // set the owning side to null (unless already changed)
            if ($boite->getPreparation() === $this) {
                $boite->setPreparation(null);
            }
        }

        return $this;
    }

    public function getDateDisparition(): ?\DateTimeInterface
    {
        return $this->dateDisparition;
    }

    public function setDateDisparition(?\DateTimeInterface $dateDisparition): self
    {
        $this->dateDisparition = $dateDisparition;

        return $this;
    }

    public function manger($date)
    {
        $this->dateDisparition = $date;
    }
}
