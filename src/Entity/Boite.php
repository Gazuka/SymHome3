<?php

namespace App\Entity;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoiteRepository")
 */
class Boite extends Entity
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Stockage", inversedBy="boites")
     */
    private $stockage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Preparation", inversedBy="boites")
     */
    private $preparation;

    /** ============================================================================================== */
    /** === FONCTIONS MAGIQUES ======================================================================= */
    /** ============================================================================================== */
    public function __construct()
    {
    }

    public function __tostring()
    {
        return $this->nom;
    }

    /** ============================================================================================== */
    /** === GETTEUR et SETTEUR ======================================================================= */
    /** ============================================================================================== */
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
    public function getStockage(): ?Stockage
    {
        return $this->stockage;
    }
    public function setStockage(?Stockage $stockage): self
    {
        $this->stockage = $stockage;

        return $this;
    }
    public function getPreparation(): ?Preparation
    {
        return $this->preparation;
    }
    public function setPreparation(?Preparation $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

    /** ============================================================================================== */
    /** === FONCTIONS PERSONNELLES =================================================================== */
    /** ============================================================================================== */
    /** Supprime le contenu de la boite et son stockage 
     * @return void
    */
    public function vider(): void
    {
        $this->preparation = null;
        $this->stockage = null;
    }
}
