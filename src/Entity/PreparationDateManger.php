<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreparationDateMangerRepository")
 */
class PreparationDateManger
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateManger;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Preparation", inversedBy="DatesManger")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Preparation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateManger(): ?\DateTimeInterface
    {
        return $this->dateManger;
    }

    public function setDateManger(\DateTimeInterface $dateManger): self
    {
        $this->dateManger = $dateManger;

        return $this;
    }

    public function getPreparation(): ?Preparation
    {
        return $this->Preparation;
    }

    public function setPreparation(?Preparation $Preparation): self
    {
        $this->Preparation = $Preparation;

        return $this;
    }
}
